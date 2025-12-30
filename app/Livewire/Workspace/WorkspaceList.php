<?php

namespace App\Livewire\Workspace;

use App\Models\Workspace;
use Livewire\Component;
use Livewire\WithPagination;

class WorkspaceList extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Workspace::accessibleBy(auth()->id())
            ->with(['owner', 'collections']);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        $workspaces = $query->latest()->paginate(12);

        return view('livewire.workspace.workspace-list', compact('workspaces'))
            ->layout('layouts.app', ['header' => 'Workspaces']);
    }
}
