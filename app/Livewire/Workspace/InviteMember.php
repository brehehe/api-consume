<?php

namespace App\Livewire\Workspace;

use App\Models\{Workspace, WorkspaceInvitation, User};
use Livewire\Component;
use Illuminate\Support\Facades\Mail;

class InviteMember extends Component
{
    public Workspace $workspace;
    public $email = '';
    public $role = 'member';

    protected $rules = [
        'email' => 'required|email',
        'role' => 'required|in:admin,member,viewer',
    ];

    public function mount($workspace)
    {
        $this->workspace = Workspace::findOrFail($workspace->id);

        if (!$this->workspace->canManage(auth()->id())) {
            abort(403);
        }

        if ($this->workspace->type !== 'team') {
            abort(403, 'Only team workspaces can invite members.');
        }
    }

    public function sendInvitation()
    {
        $this->validate();

        // Check if user already a member
        $existingUser = User::where('email', $this->email)->first();
        if ($existingUser && $this->workspace->isMember($existingUser->id)) {
            $this->addError('email', 'This user is already a member of this workspace.');
            return;
        }

        // Check existing invitation
        $existingInvitation = WorkspaceInvitation::where('workspace_id', $this->workspace->id)
            ->where('email', $this->email)
            ->where('status', 'pending')
            ->first();

        if ($existingInvitation) {
            $this->addError('email', 'An invitation has already been sent to this email.');
            return;
        }

        // Create invitation
        $invitation = WorkspaceInvitation::create([
            'workspace_id' => $this->workspace->id,
            'email' => $this->email,
            'invited_by' => auth()->id(),
            'status' => 'pending',
        ]);

        // TODO: Send email notification
        // Mail::to($this->email)->send(new WorkspaceInvitationMail($invitation));

        session()->flash('message', 'Invitation sent successfully! (Email feature will be implemented)');
        $this->reset(['email']);
    }

    public function render()
    {
        $pendingInvitations = $this->workspace->pendingInvitations()->with('inviter')->get();

        return view('livewire.workspace.invite-member', compact('pendingInvitations'))
            ->layout('layouts.app', ['header' => 'Invite Members']);
    }
}
