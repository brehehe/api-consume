<div class="relative" x-data="{ open: false }">
    <!-- Notification Bell -->
    <button
        @click="open = !open"
        class="relative p-2 rounded-lg hover:bg-slate-700 transition-colors"
    >
        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>

        @if(count($invitations) > 0)
        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        @endif
    </button>

    <!-- Dropdown -->
    <div
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-96 card-dark shadow-xl z-50"
        style="display: none;"
    >
        <div class="p-4 border-b border-slate-700">
            <h3 class="font-semibold text-slate-100">Notifications</h3>
        </div>

        @if(session()->has('message'))
        <div class="p-4 bg-green-500/10 border-b border-green-500/20">
            <p class="text-sm text-green-400">{{ session('message') }}</p>
        </div>
        @endif

        @if(session()->has('error'))
        <div class="p-4 bg-red-500/10 border-b border-red-500/20">
            <p class="text-sm text-red-400">{{ session('error') }}</p>
        </div>
        @endif

        <div class="max-h-96 overflow-y-auto">
            @forelse($invitations as $invitation)
            <div class="p-4 border-b border-slate-700 hover:bg-slate-700/30">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold flex-shrink-0">
                        {{ substr($invasion->inviter->name ?? 'U', 0, 2) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-slate-200 mb-1">
                            <span class="font-medium">{{ $invitation->inviter->name ?? 'Someone' }}</span>
                            invited you to join
                            <span class="font-medium">{{ $invitation->workspace->name }}</span>
                        </p>
                        <p class="text-xs text-slate-400 mb-3">
                            {{ $invitation->created_at->diffForHumans() }}
                        </p>

                        <div class="flex gap-2">
                            <button
                                wire:click="acceptInvitation({{ $invitation->id }})"
                                class="px-3 py-1.5 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors"
                            >
                                Accept
                            </button>
                            <button
                                wire:click="rejectInvitation({{ $invitation->id }})"
                                class="px-3 py-1.5 text-xs bg-slate-700 hover:bg-slate-600 text-white rounded transition-colors"
                            >
                                Decline
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <svg class="w-12 h-12 mx-auto mb-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-sm text-slate-400">No notifications</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@assets
<script src="//unpkg.com/alpinejs" defer></script>
@endassets
