<?php

namespace App\Livewire;

use App\Models\Workspace;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        // Workspace statistics
        $stats = [
            'total_workspaces' => $user->workspaces()->count() + $user->workspaceMemberships()->accepted()->count(),
            'owned_workspaces' => $user->workspaces()->count(),
            'private_workspaces' => $user->workspaces()->where('type', 'private')->count(),
            'public_workspaces' => $user->workspaces()->where('type', 'public')->count(),
            'team_workspaces' => $user->workspaces()->where('type', 'team')->count(),
            'pending_invitations' => $user->workspaceInvitations()->count(),
        ];

        // Recent workspaces
        $recentWorkspaces = $user->workspaces()
            ->latest()
            ->take(5)
            ->with('collections')
            ->get();

        return view('livewire.dashboard', compact('stats', 'recentWorkspaces'))
            ->layout('layouts.app', ['header' => 'Dashboard']);
    }
}
