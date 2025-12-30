<?php

namespace App\Livewire\Workspace;

use App\Models\Workspace;
use Livewire\Component;
use Illuminate\Support\Str;

class WorkspaceForm extends Component
{
    public $workspaceId;
    public $name = '';
    public $description = '';
    public $type = 'private';
    public $isEdit = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'type' => 'required|in:private,public,team',
    ];

    public function mount($workspace = null)
    {
        if ($workspace) {
            $this->isEdit = true;
            $this->workspaceId = $workspace;
            $ws = Workspace::findOrFail($workspace);

            if (!$ws->canManage(auth()->id())) {
                abort(403);
            }

            $this->name = $ws->name;
            $this->description = $ws->description;
            $this->type = $ws->type;
        }
    }

    private function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Workspace::where('slug', $slug);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            $workspace = Workspace::findOrFail($this->workspaceId);

            if (!$workspace->canManage(auth()->id())) {
                abort(403);
            }

            $workspace->update([
                'name' => $this->name,
                'slug' => $this->generateUniqueSlug($this->name, $workspace->id),
                'description' => $this->description,
                'type' => $this->type,
            ]);

            session()->flash('message', 'Workspace updated successfully!');
        } else {
            $workspace = Workspace::create([
                'name' => $this->name,
                'slug' => $this->generateUniqueSlug($this->name),
                'description' => $this->description,
                'type' => $this->type,
                'user_id' => auth()->id(),
            ]);

            // Auto-add owner as member
            $workspace->members()->create([
                'user_id' => auth()->id(),
                'role' => 'owner',
                'accepted_at' => now(),
            ]);

            session()->flash('message', 'Workspace created successfully!');
        }

        return $this->redirect(route('workspaces.detail', $workspace));
    }

    public function render()
    {
        $title = $this->isEdit ? 'Edit Workspace' : 'Create Workspace';
        return view('livewire.workspace.workspace-form')
            ->layout('layouts.app', ['header' => $title]);
    }
}
