<div class="space-y-6">
    <!-- Welcome Message -->
    <div class="card-dark p-6">
        <h3 class="text-2xl font-bold text-slate-100 mb-2">
            Welcome back, {{ auth()->user()->name }}! 👋
        </h3>
        <p class="text-slate-400">
            Here's an overview of your workspaces and activity.
        </p>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Workspaces -->
        <div class="card-dark p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 mb-1">Total Workspaces</p>
                    <p class="text-3xl font-bold text-slate-100">{{ $stats['total_workspaces'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Owned Workspaces -->
        <div class="card-dark p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 mb-1">Owned Workspaces</p>
                    <p class="text-3xl font-bold text-slate-100">{{ $stats['owned_workspaces'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Invitations -->
        <div class="card-dark p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 mb-1">Pending Invitations</p>
                    <p class="text-3xl font-bold text-slate-100">{{ $stats['pending_invitations'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Private Workspaces -->
        <div class="card-dark p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 mb-1">Private</p>
                    <p class="text-3xl font-bold text-slate-100">{{ $stats['private_workspaces'] }}</p>
                </div>
                <div class="px-3 py-1 rounded-full bg-slate-700 text-xs font-medium text-slate-300">
                    Private
                </div>
            </div>
        </div>

        <!-- Public Workspaces -->
        <div class="card-dark p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 mb-1">Public</p>
                    <p class="text-3xl font-bold text-slate-100">{{ $stats['public_workspaces'] }}</p>
                </div>
                <div class="px-3 py-1 rounded-full bg-green-700/50 text-xs font-medium text-green-300">
                    Public
                </div>
            </div>
        </div>

        <!-- Team Workspaces -->
        <div class="card-dark p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 mb-1">Team</p>
                    <p class="text-3xl font-bold text-slate-100">{{ $stats['team_workspaces'] }}</p>
                </div>
                <div class="px-3 py-1 rounded-full bg-blue-700/50 text-xs font-medium text-blue-300">
                    Team
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Workspaces -->
    @if($recentWorkspaces->count() > 0)
    <div class="card-dark">
        <div class="p-6 border-b border-slate-700">
            <h4 class="text-lg font-semibold text-slate-100">Recent Workspaces</h4>
        </div>
        <div class="divide-y divide-slate-700">
            @foreach($recentWorkspaces as $workspace)
            <a href="{{ route('workspaces.detail', $workspace) }}"
               wire:navigate
               class="block p-6 hover:bg-slate-700/50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h5 class="font-medium text-slate-100 mb-1">{{ $workspace->name }}</h5>
                        <p class="text-sm text-slate-400">
                            {{ $workspace->collections->count() }} collections •
                            <span class="capitalize">{{ $workspace->type }}</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($workspace->type === 'private')
                            <span class="px-2 py-1 rounded text-xs bg-slate-700 text-slate-300">Private</span>
                        @elseif($workspace->type === 'public')
                            <span class="px-2 py-1 rounded text-xs bg-green-700/50 text-green-300">Public</span>
                        @else
                            <span class="px-2 py-1 rounded text-xs bg-blue-700/50 text-blue-300">Team</span>
                        @endif
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @else
    <div class="card-dark p-12 text-center">
        <svg class="w-16 h-16 mx-auto mb-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
        </svg>
        <h3 class="text-lg font-medium text-slate-300 mb-2">No workspaces yet</h3>
        <p class="text-slate-400 mb-6">Create your first workspace to get started</p>
        <a href="{{ route('workspaces') }}" wire:navigate class="btn-primary inline-block">
            Create Workspace
        </a>
    </div>
    @endif
</div>
