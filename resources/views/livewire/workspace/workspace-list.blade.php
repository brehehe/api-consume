<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
        <!-- Search -->
        <div class="flex-1 max-w-md">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                class="input-dark w-full"
                placeholder="Search workspaces..."
            >
        </div>

        <!-- Filter and Create -->
        <div class="flex gap-3">
            <select wire:model.live="typeFilter" class="input-dark">
                <option value="all">All Types</option>
                <option value="private">Private</option>
                <option value="public">Public</option>
                <option value="team">Team</option>
            </select>

            <a href="{{ route('workspaces.create') }}" wire:navigate class="btn-primary whitespace-nowrap">
                + New Workspace
            </a>
        </div>
    </div>

    <!-- Workspaces Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($workspaces as $workspace)
        <a href="{{ route('workspaces.detail', $workspace) }}"
           wire:navigate
           class="card-dark p-6 hover:border-blue-500/50 transition-all group">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-slate-100 mb-1 truncate group-hover:text-blue-400 transition-colors">
                        {{ $workspace->name }}
                    </h3>
                    <p class="text-sm text-slate-400 truncate">
                        {{ $workspace->description ?: 'No description' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    <span>{{ $workspace->collections->count() }} collections</span>
                </div>

                @if($workspace->type === 'private')
                    <span class="px-2 py-1 rounded text-xs bg-slate-700 text-slate-300">Private</span>
                @elseif($workspace->type === 'public')
                    <span class="px-2 py-1 rounded text-xs bg-green-700/50 text-green-300">Public</span>
                @else
                    <span class="px-2 py-1 rounded text-xs bg-blue-700/50 text-blue-300">Team</span>
                @endif
            </div>

            <div class="mt-4 pt-4 border-t border-slate-700">
                <div class="flex items-center gap-2 text-xs text-slate-400">
                    @if($workspace->isOwner(auth()->id()))
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Owner
                        </span>
                    @else
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                            Member
                        </span>
                    @endif
                    <span>•</span>
                    <span>by {{ $workspace->owner->name }}</span>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full card-dark p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-slate-300 mb-2">No workspaces found</h3>
            <p class="text-slate-400 mb-6">
                @if($search || $typeFilter !== 'all')
                    Try adjusting your search or filters
                @else
                    Create your first workspace to get started
                @endif
            </p>
            @if(!$search && $typeFilter === 'all')
            <a href="{{ route('workspaces.create') }}" wire:navigate class="btn-primary inline-block">
                Create Workspace
            </a>
            @endif
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($workspaces->hasPages())
    <div class="mt-6">
        {{ $workspaces->links() }}
    </div>
    @endif
</div>
