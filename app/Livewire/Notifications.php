<?php

namespace App\Livewire;

use App\Models\WorkspaceInvitation;
use Livewire\Component;

class Notifications extends Component
{
    public $invitations = [];
    public $showDropdown = false;

    public function mount()
    {
        $this->loadInvitations();
    }

    public function loadInvitations()
    {
        $this->invitations = auth()->user()
            ->workspaceInvitations()
            ->with(['workspace', 'inviter'])
            ->get();
    }

    public function acceptInvitation($invitationId)
    {
        $invitation = WorkspaceInvitation::findOrFail($invitationId);

        if ($invitation->email !== auth()->user()->email) {
            return;
        }

        if ($invitation->accept()) {
            // Add user to workspace members
            $invitation->workspace->members()->create([
                'user_id' => auth()->id(),
                'role' => 'member',
                'invited_by' => $invitation->invited_by,
                'accepted_at' => now(),
            ]);

            session()->flash('message', 'Invitation accepted successfully!');
            $this->loadInvitations();
        } else {
            session()->flash('error', 'This invitation has expired.');
        }
    }

    public function rejectInvitation($invitationId)
    {
        $invitation = WorkspaceInvitation::findOrFail($invitationId);

        if ($invitation->email !== auth()->user()->email) {
            return;
        }

        $invitation->reject();
        session()->flash('message', 'Invitation rejected.');
        $this->loadInvitations();
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}
