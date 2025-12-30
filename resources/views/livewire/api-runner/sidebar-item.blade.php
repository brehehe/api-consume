@props(['collection', 'activeRequest' => null, 'openCollectionIds' => []])

<div x-data="{ expanded: {{ in_array($collection->id, $openCollectionIds) ? 'true' : 'false' }} }" class="ml-2">
    <!-- Collection Header -->
    <div class="flex items-center justify-between group mb-1">
        <button
            @click="expanded = !expanded"
            class="flex items-center gap-1.5 flex-1 min-w-0 text-left px-2 py-1.5 rounded hover:bg-slate-700/50 transition-colors"
        >
            <svg
                class="w-3 h-3 text-slate-500 transition-transform duration-200"
                :class="{ 'rotate-90': expanded }"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <div class="flex items-center gap-2 overflow-hidden">
                <svg class="w-4 h-4 text-yellow-600/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                <span class="text-sm font-medium text-slate-300 truncate">{{ $collection->name }}</span>
            </div>
        </button>
        <div class="flex items-center gap-1 pr-2 opacity-0 group-hover:opacity-100 transition-opacity">
            <!-- Add Request -->
            <button
                wire:click.stop="openCreateRequestModal({{ $collection->id }})"
                class="p-1 text-slate-500 hover:text-green-400 transition-colors"
                title="Add Request"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </button>
            <!-- Add Folder -->
            <button
                wire:click.stop="openCreateCollectionModal({{ $collection->id }})"
                class="p-1 text-slate-500 hover:text-yellow-400 transition-colors"
                title="Add Folder"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
            </button>
            <!-- Delete -->
            <button
                wire:click.stop="confirmDelete('collection', {{ $collection->id }})"
                class="p-1 text-slate-500 hover:text-red-400 transition-colors"
                title="Delete Collection"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Children (Nested Collections & Requests) -->
    <div x-show="expanded" x-collapse style="display: none;" class="ml-2 border-l border-slate-700 pl-1 space-y-0.5">
        <!-- Requests in this collection -->
        @foreach($collection->requests as $request)
        <div class="group/req relative flex items-center">
            <a
                href="{{ route('api-runner.workspace.request.collection', [$collection->workspace_id, $request->id, $collection->id]) }}" wire:navigate
                class="w-full text-left px-2 py-1.5 rounded text-sm transition-all flex items-center gap-2 {{ $activeRequest?->id === $request->id ? 'bg-blue-600/20 text-blue-100' : 'text-slate-400 hover:bg-slate-700/30' }}"
            >
                <span class="font-mono font-bold text-[10px] uppercase w-8 {{ $activeRequest?->id === $request->id ? 'text-blue-400' : 'text-slate-500 group-hover/req:text-slate-300' }}">
                    {{ $request->method }}
                </span>
                <span class="truncate {{ $activeRequest?->id === $request->id ? 'text-white' : '' }}">
                    {{ $request->name }}
                </span>
            </a>

            <!-- Delete Request Button -->
            <button
                wire:click.stop="confirmDelete('request', {{ $request->id }})"
                class="absolute right-2 opacity-0 group-hover/req:opacity-100 p-1 text-slate-500 hover:text-red-400 transition-all bg-slate-800/80 rounded"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
        @endforeach

        <!-- Nested Collections -->
        @foreach($collection->children as $childCollection)
            @include('livewire.api-runner.sidebar-item', [
                'collection' => $childCollection,
                'activeRequest' => $activeRequest,
                'openCollectionIds' => $openCollectionIds
            ])
        @endforeach
    </div>
</div>
