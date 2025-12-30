<?php

namespace App\Livewire\Workspace;

use App\Models\Workspace;
use Livewire\Component;

class WorkspaceDetail extends Component
{
    public Workspace $workspace;
    public $showInviteModal = false;

    public function mount($workspace)
    {
        $this->workspace = Workspace::with(['owner', 'collections.requests', 'members.user'])
            ->findOrFail($workspace->id);

        if (!$this->workspace->isMember(auth()->id()) && $this->workspace->type !== 'public') {
            abort(403);
        }
    }

    public function deleteWorkspace()
    {
        if (!$this->workspace->isOwner(auth()->id())) {
            abort(403);
        }

        $this->workspace->delete();
        session()->flash('message', 'Workspace deleted successfully.');
        return $this->redirect(route('workspaces'), navigate: true);
    }

    public function removeMember($memberId)
    {
        if (!$this->workspace->canManage(auth()->id())) {
            abort(403);
        }

        $this->workspace->members()->where('id', $memberId)->delete();
        $this->workspace->load('members.user');
    }

    public function render()
    {
        return view('livewire.workspace.workspace-detail')
            ->layout('layouts.app', ['header' => $this->workspace->name]);
    }
}
