<div class="space-y-6">
    <!-- Workspace Header -->
    <div class="card-dark p-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <h3 class="text-2xl font-bold text-slate-100">{{ $workspace->name }}</h3>
                    @if($workspace->type === 'private')
                        <span class="px-3 py-1 rounded-full text-xs bg-slate-700 text-slate-300">Private</span>
                    @elseif($workspace->type === 'public')
                        <span class="px-3 py-1 rounded-full text-xs bg-green-700/50 text-green-300">Public</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs bg-blue-700/50 text-blue-300">Team</span>
                    @endif
                </div>
                <p class="text-slate-400">{{ $workspace->description ?: 'No description provided' }}</p>
            </div>

            @if($workspace->canManage(auth()->id()))
            <div class="flex gap-2">
                <a href="{{ route('workspaces.edit', $workspace) }}" wire:navigate class="btn-secondary">
                    Edit
                </a>
                <button wire:click="deleteWorkspace" wire:confirm="Are you sure you want to delete this workspace?" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                    Delete
                </button>
            </div>
            @endif
        </div>

        <div class="flex items-center gap-4 text-sm text-slate-400 border-t border-slate-700 pt-4">
            <span>Created by {{ $workspace->owner->name }}</span>
            <span>•</span>
            <span>{{ $workspace->collections->count() }} collections</span>
            <span>•</span>
            <span>{{ $workspace->members->count() }} members</span>
        </div>
    </div>

    <!-- Collections -->
    <div class="card-dark">
        <div class="p-6 border-b border-slate-700 flex items-center justify-between">
            <h4 class="text-lg font-semibold text-slate-100">API Collections</h4>
            <a href="{{ route('api-runner.workspace', $workspace) }}" wire:navigate class="btn-primary text-sm">
                Open in API Runner
            </a>
        </div>

        @if($workspace->collections->count() > 0)
        <div class="divide-y divide-slate-700">
            @foreach($workspace->collections as $collection)
            <div class="p-6 hover:bg-slate-700/30 transition-colors">
                <h5 class="font-medium text-slate-100 mb-1">{{ $collection->name }}</h5>
                <p class="text-sm text-slate-400 mb-2">{{ $collection->description }}</p>
                <p class="text-xs text-slate-500">{{ $collection->requests->count() }} requests</p>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="w-12 h-12 mx-auto mb-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            <p class="text-sm text-slate-400">No collections yet</p>
        </div>
        @endif
    </div>

    <!-- Team Members (for team workspaces) -->
    @if($workspace->type === 'team')
    <div class="card-dark">
        <div class="p-6 border-b border-slate-700 flex items-center justify-between">
            <h4 class="text-lg font-semibold text-slate-100">Team Members</h4>
            @if($workspace->canManage(auth()->id()))
            <a href="{{ route('workspaces.invite', $workspace) }}" wire:navigate class="btn-primary text-sm">
                Invite Member
            </a>
            @endif
        </div>

        <div class="divide-y divide-slate-700">
            @foreach($workspace->members as $member)
            <div class="p-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                        {{ substr($member->user->name, 0, 2) }}
                    </div>
                    <div>
                        <p class="font-medium text-slate-200">{{ $member->user->name }}</p>
                        <p class="text-sm text-slate-400">{{ $member->user->email }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-full text-xs bg-slate-700 text-slate-300 capitalize">
                        {{ $member->role }}
                    </span>
                    @if($workspace->canManage(auth()->id()) && !$workspace->isOwner($member->user_id))
                    <button wire:click="removeMember({{ $member->id }})" wire:confirm="Remove this member?" class="text-red-400 hover:text-red-300 text-sm">
                        Remove
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
