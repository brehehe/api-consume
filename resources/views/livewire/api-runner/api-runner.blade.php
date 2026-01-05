<div class="h-screen flex flex-col">
    <!-- Top Bar -->
    <div class="bg-slate-800 border-b border-slate-700 px-6 py-3 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('workspaces.detail', $workspace) }}" wire:navigate class="flex items-center gap-2 text-slate-300 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="font-medium">Back to Workspace</span>
            </a>
            <div class="h-6 w-px bg-slate-600"></div>
            <div>
                <h1 class="text-lg font-semibold text-slate-100">{{ $workspace->name }}</h1>
                <!-- <p class="text-xs text-slate-400">API Runner</p> -->
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- Environment Selector -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 bg-slate-700 hover:bg-slate-600 rounded transition-colors text-slate-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                    <span class="text-sm font-medium">
                        {{ $activeEnvironment ? $activeEnvironment->name : 'No Environment' }}
                    </span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Dropdown -->
                <div x-show="open" @click.away="open = false" class="absolute right-0 top-full mt-2 w-64 bg-slate-800 border border-slate-700 rounded shadow-xl z-50 py-1" style="display: none;">
                    <div class="px-3 py-2 border-b border-slate-700">
                        <p class="text-xs font-semibold text-slate-400 uppercase">Environments</p>
                    </div>

                    @if($environments && $environments->count() > 0)
                        @foreach($environments as $env)
                            <button
                                wire:click="selectEnvironment('{{ $env->id }}')"
                                @click="open = false"
                                class="w-full text-left px-3 py-2 text-sm hover:bg-slate-700 transition-colors flex items-center justify-between {{ $activeEnvironment && $activeEnvironment->id === $env->id ? 'bg-slate-700/50 text-blue-400' : 'text-slate-300' }}"
                            >
                                <span>{{ $env->name }}</span>
                                @if($activeEnvironment && $activeEnvironment->id === $env->id)
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                            </button>
                        @endforeach
                        <div class="border-t border-slate-700 my-1"></div>
                    @else
                        <div class="px-3 py-2 text-xs text-slate-500 italic">No environments yet</div>
                    @endif

                    <button
                        wire:click="openEnvironmentModal"
                        @click="open = false"
                        class="w-full text-left px-3 py-2 text-sm text-blue-400 hover:bg-slate-700 transition-colors flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Manage Environments
                    </button>
                </div>
            </div>

            <div class="h-6 w-px bg-slate-600"></div>

            <div class="text-sm text-slate-400">
                {{ auth()->user()->name }}
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex gap-0 overflow-hidden">
        <!-- Sidebar - Collections -->
        <div class="w-80 bg-slate-800 border-r border-slate-700 overflow-y-auto flex-shrink-0" x-data>
            <div class="p-4 border-b border-slate-700 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-slate-100 mb-1">Explorer</h3>
                    <p class="text-xs text-slate-400 max-w-[150px] truncate">{{ $workspace->name }}</p>
                </div>
                <!-- Main Add Button -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="p-1.5 hover:bg-slate-700 rounded text-slate-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" class="absolute right-0 top-full mt-2 w-48 bg-slate-800 border border-slate-700 rounded shadow-xl z-50 py-1" style="display: none;">
                        <button wire:click="openCreateRequestModal()" @click="open = false" class="w-full text-left px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white">
                            New Request
                        </button>
                        <button wire:click="openCreateCollectionModal()" @click="open = false" class="w-full text-left px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white">
                            New Collection
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-2 space-y-0.5">
                 <!-- Root Requests -->
                @foreach($workspace->rootRequests as $request)
                <div class="group relative flex items-center">
                    <a
                        href="{{ route('api-runner.workspace.request', [$request->workspace_id, $request->id]) }}" wire:navigate
                        class="w-full text-left px-2 py-1.5 rounded text-sm transition-all flex items-center gap-2 {{ $selectedRequest?->id === $request->id ? 'bg-blue-600/20 text-blue-100' : 'text-slate-400 hover:bg-slate-700/30' }}"
                    >
                        <span class="font-mono font-bold text-[10px] uppercase w-8 {{ $selectedRequest?->id === $request->id ? 'text-blue-400' : 'text-slate-500' }}">
                            {{ $request->method }}
                        </span>
                        <span class="truncate {{ $selectedRequest?->id === $request->id ? 'text-white' : '' }}">
                            {{ $request->name }}
                        </span>
</a>

                    <!-- Delete Request Button -->
                    <button
                        wire:click.stop="confirmDelete('request', {{ $request->id }})"
                        class="absolute right-2 opacity-0 group-hover:opacity-100 p-1 text-slate-500 hover:text-red-400 transition-all bg-slate-800/80 rounded"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
                @endforeach

                <!-- Root Collections -->
                @foreach($workspace->rootCollections as $collection)
                    @include('livewire.api-runner.sidebar-item', [
                        'collection' => $collection,
                        'activeRequest' => $selectedRequest,
                        'openCollectionIds' => $openCollectionIds
                    ])
                @endforeach

                @if($workspace->rootRequests->isEmpty() && $workspace->rootCollections->isEmpty())
                <div class="text-center py-12">
                     <p class="text-xs text-slate-500">No requests found</p>
                     <button wire:click="openCreateRequestModal()" class="text-xs text-blue-400 hover:text-blue-300 mt-2">Create one now</button>
                </div>
                @endif
            </div>

            <!-- Modals -->
            <!-- Create Collection Modal -->
            @if($showCreateCollectionModal)
            <div class="fixed inset-0 z-[60] bg-black/50 flex items-center justify-center p-4">
                <div class="bg-slate-800 rounded-lg shadow-xl border border-slate-700 w-full max-w-sm" @click.outside="$wire.set('showCreateCollectionModal', false)">
                    <div class="p-4 border-b border-slate-700">
                        <h3 class="font-semibold text-slate-100">New Collection</h3>
                    </div>
                    <div class="p-4">
                        <input type="text" wire:model="newItemName" class="input-dark w-full mb-2" placeholder="Collection Name" autofocus>
                        @error('newItemName') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        <div class="flex justify-end gap-2 mt-4">
                            <button wire:click="$set('showCreateCollectionModal', false)" class="px-3 py-1.5 text-sm text-slate-400 hover:text-white">Cancel</button>
                            <button wire:click="createCollection" class="btn-primary px-4 py-1.5 text-sm">Create</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Create Request Modal -->
            @if($showCreateRequestModal)
            <div class="fixed inset-0 z-[60] bg-black/50 flex items-center justify-center p-4">
                <div class="bg-slate-800 rounded-lg shadow-xl border border-slate-700 w-full max-w-sm" @click.outside="$wire.set('showCreateRequestModal', false)">
                    <div class="p-4 border-b border-slate-700">
                        <h3 class="font-semibold text-slate-100">New Request</h3>
                    </div>
                    <div class="p-4">
                        <input type="text" wire:model="newItemName" class="input-dark w-full mb-2" placeholder="Request Name (e.g., Get Users)" autofocus>
                        @error('newItemName') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        <div class="flex justify-end gap-2 mt-4">
                            <button wire:click="$set('showCreateRequestModal', false)" class="px-3 py-1.5 text-sm text-slate-400 hover:text-white">Cancel</button>
                            <button wire:click="createRequest" class="btn-primary px-4 py-1.5 text-sm">Create</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Delete Confirmation Modal -->
            @if($showDeleteModal)
            <div class="fixed inset-0 z-[60] bg-black/50 flex items-center justify-center p-4">
                <div class="bg-slate-800 rounded-lg shadow-xl border border-slate-700 w-full max-w-sm" @click.outside="$wire.set('showDeleteModal', false)">
                    <div class="p-4">
                        <h3 class="font-semibold text-slate-100 mb-2">Confirm Delete</h3>
                        <p class="text-sm text-slate-400 mb-4">Are you sure you want to delete this {{ $itemToDeleteType }}? This action cannot be undone.</p>
                        <div class="flex justify-end gap-2">
                            <button wire:click="$set('showDeleteModal', false)" class="px-3 py-1.5 text-sm text-slate-400 hover:text-white">Cancel</button>
                            <button wire:click="deleteItem" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded text-sm transition-colors">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Environment Management Modal -->
            @if($showEnvironmentModal)
            <div class="fixed inset-0 z-[60] bg-black/50 flex items-center justify-center p-4">
                <div class="bg-slate-800 rounded-lg shadow-xl border border-slate-700 w-full max-w-4xl max-h-[90vh] flex flex-col" @click.outside="$wire.set('showEnvironmentModal', false)">
                    <!-- Modal Header -->
                    <div class="p-4 border-b border-slate-700 flex items-center justify-between">
                        <h3 class="font-semibold text-slate-100">Manage Environments</h3>
                        <button wire:click="$set('showEnvironmentModal', false)" class="text-slate-400 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Tabs -->
                    <div class="flex items-center gap-1 px-4 border-b border-slate-700 bg-slate-800/50">
                        <button
                            wire:click="$set('environmentTab', 'list')"
                            class="px-4 py-2 text-sm border-b-2 transition-colors {{ $environmentTab === 'list' ? 'border-blue-500 text-blue-400 font-medium' : 'border-transparent text-slate-400 hover:text-slate-200' }}"
                        >
                            Environments
                        </button>
                        @if($editingEnvironment)
                        <button
                            wire:click="$set('environmentTab', 'variables')"
                            class="px-4 py-2 text-sm border-b-2 transition-colors {{ $environmentTab === 'variables' ? 'border-blue-500 text-blue-400 font-medium' : 'border-transparent text-slate-400 hover:text-slate-200' }}"
                        >
                            Variables: {{ $editingEnvironment->name }}
                        </button>
                        @endif
                    </div>

                    <!-- Modal Content -->
                    <div class="flex-1 overflow-y-auto p-4">
                        <!-- Environments List Tab -->
                        @if($environmentTab === 'list')
                        <div>
                            <!-- Create New Environment Form -->
                            <div class="bg-slate-900/50 p-4 rounded border border-slate-700 mb-4">
                                <h4 class="text-sm font-semibold text-slate-300 mb-3">Create New Environment</h4>
                                <div class="space-y-3">
                                    <div>
                                        <input
                                            type="text"
                                            wire:model="newEnvironmentName"
                                            class="input-dark w-full"
                                            placeholder="Environment Name (e.g., Development, Production)"
                                        >
                                        @error('newEnvironmentName') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <textarea
                                            wire:model="newEnvironmentDescription"
                                            class="input-dark w-full"
                                            rows="2"
                                            placeholder="Description (optional)"
                                        ></textarea>
                                    </div>
                                    <button wire:click="createEnvironment" class="btn-primary px-4 py-2 text-sm w-full">
                                        Create Environment
                                    </button>
                                </div>
                            </div>

                            <!-- Environments List -->
                            <div class="space-y-2">
                                <h4 class="text-sm font-semibold text-slate-400 uppercase mb-2">Your Environments</h4>
                                @if($environments && $environments->count() > 0)
                                    @foreach($environments as $env)
                                    <div class="bg-slate-900/30 p-3 rounded border border-slate-700 flex items-center justify-between group hover:border-slate-600 transition-colors">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <h5 class="font-medium text-slate-200">{{ $env->name }}</h5>
                                                @if($activeEnvironment && $activeEnvironment->id === $env->id)
                                                    <span class="text-xs bg-blue-600/20 text-blue-400 px-2 py-0.5 rounded">Active</span>
                                                @endif
                                            </div>
                                            @if($env->description)
                                                <p class="text-xs text-slate-400 mt-1">{{ $env->description }}</p>
                                            @endif
                                            <p class="text-xs text-slate-500 mt-1">{{ $env->variables->count() }} variable(s)</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button
                                                wire:click="editEnvironment('{{ $env->id }}')"
                                                class="px-3 py-1.5 text-xs bg-slate-700 hover:bg-slate-600 text-slate-300 rounded transition-colors"
                                            >
                                                Edit Variables
                                            </button>
                                            @if(!$activeEnvironment || $activeEnvironment->id !== $env->id)
                                                <button
                                                    wire:click="selectEnvironment('{{ $env->id }}')"
                                                    class="px-3 py-1.5 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors"
                                                >
                                                    Activate
                                                </button>
                                            @endif
                                            <button
                                                wire:click="deleteEnvironment('{{ $env->id }}')"
                                                class="p-1.5 text-slate-500 hover:text-red-400 transition-colors"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-8 text-slate-500">
                                        <p class="text-sm">No environments yet. Create one above to get started.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Variables Editor Tab -->
                        @if($environmentTab === 'variables' && $editingEnvironment)
                        <div>
                            <div class="mb-4 flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-300">Variables for {{ $editingEnvironment->name }}</h4>
                                    <p class="text-xs text-slate-500 mt-1">Use @{{variable_name}} syntax in your requests</p>
                                </div>
                                <button wire:click="addEnvironmentVariable" class="btn-primary px-3 py-1.5 text-sm flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add Variable
                                </button>
                            </div>

                            <!-- Variables Table -->
                            <div class="space-y-2">
                                @if(!empty($environmentVariables))
                                    @foreach($environmentVariables as $index => $variable)
                                    <div class="bg-slate-900/30 p-3 rounded border border-slate-700" wire:key="env-var-{{ $index }}">
                                        <div class="grid grid-cols-12 gap-2 items-start">
                                            <!-- Enabled Checkbox -->
                                            <div class="col-span-1 pt-2">
                                                <input
                                                    type="checkbox"
                                                    wire:model="environmentVariables.{{ $index }}.enabled"
                                                    class="rounded bg-slate-700 border-slate-600 text-blue-500"
                                                >
                                            </div>

                                            <!-- Key Input -->
                                            <div class="col-span-3">
                                                <input
                                                    type="text"
                                                    wire:model="environmentVariables.{{ $index }}.key"
                                                    placeholder="variable_name"
                                                    class="input-dark w-full text-sm font-mono"
                                                >
                                            </div>

                                            <!-- Value Input -->
                                            <div class="col-span-4">
                                                @if($variable['is_secret'])
                                                    <input
                                                        type="password"
                                                        wire:model="environmentVariables.{{ $index }}.value"
                                                        placeholder="Value"
                                                        class="input-dark w-full text-sm"
                                                    >
                                                @else
                                                    <input
                                                        type="text"
                                                        wire:model="environmentVariables.{{ $index }}.value"
                                                        placeholder="Value"
                                                        class="input-dark w-full text-sm"
                                                    >
                                                @endif
                                            </div>

                                            <!-- Description Input -->
                                            <div class="col-span-3">
                                                <input
                                                    type="text"
                                                    wire:model="environmentVariables.{{ $index }}.description"
                                                    placeholder="Description (optional)"
                                                    class="input-dark w-full text-sm"
                                                >
                                            </div>

                                            <!-- Actions -->
                                            <div class="col-span-1 flex items-center gap-1">
                                                <button
                                                    wire:click="environmentVariables.{{ $index }}.is_secret = !environmentVariables.{{ $index }}.is_secret"
                                                    class="p-1 {{ $variable['is_secret'] ? 'text-yellow-500' : 'text-slate-500' }} hover:text-yellow-400 transition-colors"
                                                    title="Toggle secret"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                    </svg>
                                                </button>
                                                <button
                                                    wire:click="removeEnvironmentVariable({{ $index }})"
                                                    class="p-1 text-slate-500 hover:text-red-400 transition-colors"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-8 text-slate-500">
                                        <p class="text-sm">No variables yet. Click "Add Variable" to create one.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Save/Cancel Buttons -->
                            <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-slate-700">
                                <button
                                    wire:click="$set('environmentTab', 'list')"
                                    class="px-4 py-2 text-sm text-slate-400 hover:text-white"
                                >
                                    Back to List
                                </button>
                                <button
                                    wire:click="saveEnvironmentVariables"
                                    class="btn-primary px-6 py-2 text-sm"
                                >
                                    Save Variables
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-row min-w-0 bg-slate-900 border-l border-slate-700">
            @if($selectedRequest)
            <!-- Request Editor (Left Pane) -->
            <div class="w-1/2 flex flex-col border-r border-slate-700 bg-slate-800">
                <!-- Top Bar: Method & URL -->
                <div class="p-4 border-b border-slate-700 flex items-center gap-3 bg-slate-800">
                    <select wire:model="requestMethod" class="input-dark w-28 font-mono font-bold text-sm uppercase">
                        <option value="GET">GET</option>
                        <option value="POST">POST</option>
                        <option value="PUT">PUT</option>
                        <option value="PATCH">PATCH</option>
                        <option value="DELETE">DELETE</option>
                        <option value="HEAD">HEAD</option>
                        <option value="OPTIONS">OPTIONS</option>
                    </select>
                    <button
                        wire:click="executeRequest"
                        wire:loading.attr="disabled"
                        class="btn-primary px-6 whitespace-nowrap flex items-center gap-2">
                        <span wire:loading.remove wire:target="executeRequest">Send</span>
                        <span wire:loading wire:target="executeRequest">Sending...</span>
                        <svg wire:loading.remove wire:target="executeRequest" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>

                <div class="p-4 border-b border-slate-700 flex items-center gap-3 bg-slate-800">
                    <textarea
                        name="requestUrl"
                        id="requestUrl"
                        wire:model.lazy="requestUrl"
                        x-data="{
                            resize() {
                                $el.style.height = 'auto';
                                $el.style.height = $el.scrollHeight + 'px'
                            }
                        }"
                        x-init="resize(); $watch('$wire.requestUrl', () => $nextTick(() => resize()))"
                        @input="resize()"
                        rows="1"
                        class="flex-1 font-mono text-sm bg-transparent border-0 focus:ring-0 p-0 resize-none overflow-hidden text-slate-100 placeholder-slate-400 w-full focus:outline-none"
                        placeholder="https://example.com/api"
                    ></textarea>
                </div>

                <!-- Tabs & Content -->
                <div class="flex-1 flex flex-col min-h-0 bg-slate-900/50">
                    <!-- Tabs -->
                    <div class="flex items-center gap-1 px-4 border-b border-slate-700 bg-slate-800">
                        <button wire:click="$set('activeTab', 'params')" class="px-4 py-2 text-sm border-b-2 transition-colors {{ $activeTab === 'params' ? 'border-blue-500 text-blue-400 font-medium' : 'border-transparent text-slate-400 hover:text-slate-200' }}">Params</button>
                        <button wire:click="$set('activeTab', 'body')" class="px-4 py-2 text-sm border-b-2 transition-colors {{ $activeTab === 'body' ? 'border-blue-500 text-blue-400 font-medium' : 'border-transparent text-slate-400 hover:text-slate-200' }}">Body</button>
                        <button wire:click="$set('activeTab', 'auth')" class="px-4 py-2 text-sm border-b-2 transition-colors {{ $activeTab === 'auth' ? 'border-blue-500 text-blue-400 font-medium' : 'border-transparent text-slate-400 hover:text-slate-200' }}">Auth</button>
                        <button wire:click="$set('activeTab', 'headers')" class="px-4 py-2 text-sm border-b-2 transition-colors flex items-center gap-2 {{ $activeTab === 'headers' ? 'border-blue-500 text-blue-400 font-medium' : 'border-transparent text-slate-400 hover:text-slate-200' }}">
                            Headers
                            @if(count($requestHeaders) > 0)
                            <span class="text-[10px] bg-slate-700 px-1.5 rounded-full text-slate-300">{{ count($requestHeaders) }}</span>
                            @endif
                        </button>
                    </div>

                    <!-- Tab Content -->
                    <div class="flex-1 overflow-y-auto p-0 relative">
                        <!-- Params Tab -->
                        @if($activeTab === 'params')
                        <div class="p-4">
                            <div class="mb-4 flex items-center justify-between">
                                <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Query Parameters</h4>
                                <button wire:click="addQueryParam" class="text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1 font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Add Param
                                </button>
                            </div>
                            <div class="space-y-1">
                                @if(empty($requestQueryParams))
                                    <div class="text-center py-8 text-slate-500 text-sm italic">No query parameters. <button wire:click="addQueryParam" class="text-blue-400 underline">Add one</button></div>
                                @else
                                    @foreach($requestQueryParams as $index => $param)
                                    <div class="flex items-center gap-2 group" wire:key="param-{{ $index }}">
                                        <div class="pt-1.5"><input type="checkbox" wire:model.blur="requestQueryParams.{{ $index }}.enabled" class="rounded bg-slate-700 border-slate-600 text-blue-500 focus:ring-offset-slate-900"></div>
                                        <input type="text" wire:model.blur="requestQueryParams.{{ $index }}.key" placeholder="Key" class="input-dark flex-1 sm:w-1/4 h-8 text-sm">
                                        <input type="text" wire:model.blur="requestQueryParams.{{ $index }}.value" placeholder="Value" class="input-dark flex-1 sm:w-1/4 h-8 text-sm">
                                        <button wire:click="removeQueryParam({{ $index }})" class="p-1 text-slate-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Headers Tab -->
                        @if($activeTab === 'headers')
                        <div class="p-4">
                            <div class="mb-4 flex items-center justify-between">
                                <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Request Headers</h4>
                                <button wire:click="addHeader" class="text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1 font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Add Header
                                </button>
                            </div>
                            <div class="space-y-1">
                                @if(empty($requestHeaders))
                                    <div class="text-center py-8 text-slate-500 text-sm italic">No active headers. <button wire:click="addHeader" class="text-blue-400 underline">Add one</button></div>
                                @else
                                    @foreach($requestHeaders as $index => $header)
                                    <div class="flex items-center gap-2 group" wire:key="header-{{ $index }}">
                                        <div class="pt-1.5"><input type="checkbox" wire:model.blur="requestHeaders.{{ $index }}.enabled" class="rounded bg-slate-700 border-slate-600 text-blue-500 focus:ring-offset-slate-900"></div>
                                        <input type="text" wire:model.blur="requestHeaders.{{ $index }}.key" placeholder="Key" class="input-dark flex-1 sm:w-1/4 h-8 text-sm">
                                        <input type="text" wire:model.blur="requestHeaders.{{ $index }}.value" placeholder="Value" class="input-dark flex-1 sm:w-1/4 h-8 text-sm">
                                        <button wire:click="removeHeader({{ $index }})" class="p-1 text-slate-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Body Tab -->
                        @if($activeTab === 'body')
                        <div class="absolute inset-0 flex flex-col">
                            <div class="p-2 border-b border-slate-700 flex items-center gap-4 bg-slate-800/30">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-slate-500">Content Type:</span>
                                    <select wire:model.live="requestBodyType" class="input-dark py-1 text-xs w-32">
                                        <option value="none">No Body</option>
                                        <option value="json">JSON</option>
                                        <option value="form-data">Form Data</option>
                                        <option value="x-www-form-urlencoded">x-www-form-urlencoded</option>
                                        <option value="raw">Raw</option>
                                    </select>
                                </div>
                                @if($requestBodyType === 'json')
                                <span class="text-xs text-green-500">application/json</span>
                                @elseif($requestBodyType === 'form-data')
                                <span class="text-xs text-blue-500">multipart/form-data</span>
                                @elseif($requestBodyType === 'x-www-form-urlencoded')
                                <span class="text-xs text-purple-500">application/x-www-form-urlencoded</span>
                                @endif
                            </div>

                            <div class="flex-1 overflow-y-auto p-4">
                                @if($requestBodyType === 'json' || $requestBodyType === 'raw')
                                    <textarea
                                        wire:model.blur="requestBody"
                                        class="w-full h-full bg-slate-900 p-4 text-sm font-mono text-slate-300 focus:outline-none resize-none rounded border border-slate-700"
                                        placeholder="{{ $requestBodyType === 'json' ? '{ &quot;key&quot;: &quot;value&quot;}' : 'Raw content' }}"
                                    ></textarea>

                                @elseif($requestBodyType === 'form-data')
                                    <div class="mb-4 flex items-center justify-between">
                                        <h4 class="text-xs font-semibold text-slate-500 uppercase">Form Data</h4>
                                        <button wire:click="addFormData" class="text-xs text-blue-400 hover:text-blue-300">Add Item</button>
                                    </div>
                                    <div class="space-y-2">
                                        @foreach($requestFormData as $index => $item)
                                        <div class="flex items-start gap-2" wire:key="form-data-{{ $index }}">
                                            <div class="pt-2"><input type="checkbox" wire:model.blur="requestFormData.{{ $index }}.enabled" class="rounded bg-slate-700 border-slate-600 text-blue-500"></div>
                                            <input type="text" wire:model.blur="requestFormData.{{ $index }}.key" placeholder="Key" class="input-dark w-1/4 h-8 text-sm">

                                            <div class="flex-1">
                                                <select wire:model.live="requestFormData.{{ $index }}.type" class="input-dark w-full h-8 text-xs mb-1">
                                                    <option value="text">Text</option>
                                                    <option value="file">File</option>
                                                </select>

                                                @if($item['type'] === 'file')
                                                    <input type="file" wire:model="requestFormData.{{ $index }}.file" class="text-xs text-slate-400 file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-slate-700 file:text-slate-300 hover:file:bg-slate-600 block w-full">
                                                    @if(isset($item['file']))
                                                        <span class="text-[10px] text-green-400 block mt-1">Selected: {{ $item['file']->getClientOriginalName() }}</span>
                                                    @endif
                                                @else
                                                    <input type="text" wire:model.blur="requestFormData.{{ $index }}.value" placeholder="Value" class="input-dark w-full h-8 text-sm">
                                                @endif
                                            </div>

                                            <button wire:click="removeFormData({{ $index }})" class="p-1 text-slate-500 hover:text-red-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                        @endforeach
                                        @if(empty($requestFormData))
                                            <div class="text-center py-4 text-slate-500 text-sm italic">No form data. <button wire:click="addFormData" class="text-blue-400 underline">Add one</button></div>
                                        @endif
                                    </div>

                                @elseif($requestBodyType === 'x-www-form-urlencoded')
                                    <div class="mb-4 flex items-center justify-between">
                                        <h4 class="text-xs font-semibold text-slate-500 uppercase">x-www-form-urlencoded</h4>
                                        <button wire:click="addFormUrlEncoded" class="text-xs text-blue-400 hover:text-blue-300">Add Item</button>
                                    </div>
                                    <div class="space-y-2">
                                        @foreach($requestFormUrlEncoded as $index => $item)
                                        <div class="flex items-center gap-2" wire:key="urlencoded-{{ $index }}">
                                            <div class="pt-1.5"><input type="checkbox" wire:model.blur="requestFormUrlEncoded.{{ $index }}.enabled" class="rounded bg-slate-700 border-slate-600 text-blue-500"></div>
                                            <input type="text" wire:model.blur="requestFormUrlEncoded.{{ $index }}.key" placeholder="Key" class="input-dark flex-1 h-8 text-sm">
                                            <input type="text" wire:model.blur="requestFormUrlEncoded.{{ $index }}.value" placeholder="Value" class="input-dark flex-1 h-8 text-sm">
                                            <button wire:click="removeFormUrlEncoded({{ $index }})" class="p-1 text-slate-500 hover:text-red-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                        @endforeach
                                        @if(empty($requestFormUrlEncoded))
                                            <div class="text-center py-4 text-slate-500 text-sm italic">No items. <button wire:click="addFormUrlEncoded" class="text-blue-400 underline">Add one</button></div>
                                        @endif
                                    </div>

                                @else
                                    <div class="flex-1 flex items-center justify-center text-slate-500 text-sm h-full">
                                        <div class="text-center">
                                            <p class="mb-2">This request has no body</p>
                                            <div class="flex gap-2 justify-center">
                                                <button wire:click="$set('requestBodyType', 'json')" class="text-blue-400 hover:underline text-xs">JSON</button>
                                                <span class="text-slate-600">|</span>
                                                <button wire:click="$set('requestBodyType', 'form-data')" class="text-blue-400 hover:underline text-xs">Form Data</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Auth Tab -->
                        @if($activeTab === 'auth')
                        <div class="p-4 max-w-2xl">
                             <div class="mb-6">
                                <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase">Authentication Type</label>
                                <select wire:model="requestAuthType" class="input-dark w-full max-w-xs">
                                    <option value="none">No Authentication</option>
                                    <option value="bearer">Bearer Token</option>
                                    <option value="basic">Basic Auth</option>
                                </select>
                            </div>

                            @if($requestAuthType === 'bearer')
                            <div class="bg-slate-800/50 p-4 rounded border border-slate-700/50">
                                <label class="block text-xs text-slate-400 mb-1">Token</label>
                                <input type="text" wire:model="requestAuthData.token" class="input-dark w-full" placeholder="Bearer Token">
                            </div>
                            @endif

                            @if($requestAuthType === 'basic')
                            <div class="bg-slate-800/50 p-4 rounded border border-slate-700/50 space-y-3">
                                <div>
                                    <label class="block text-xs text-slate-400 mb-1">Username</label>
                                    <input type="text" wire:model="requestAuthData.username" class="input-dark w-full" placeholder="Username">
                                </div>
                                <div>
                                    <label class="block text-xs text-slate-400 mb-1">Password</label>
                                    <input type="password" wire:model="requestAuthData.password" class="input-dark w-full" placeholder="Password">
                                </div>
                            </div>
                            @endif

                            @if($requestAuthType === 'none')
                                <div class="text-slate-500 text-sm italic">This request does not use any authorization.</div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Response Viewer (Right Pane) -->
            <div
                x-data="{ copied: false }"
                class="w-1/2 flex flex-col overflow-hidden bg-slate-900 border-l border-slate-700"
            >
                @if($response)
                <div class="bg-slate-800 border-b border-slate-700 px-6 py-3 flex items-center justify-between flex-shrink-0">
                    <h3 class="font-semibold text-slate-100">Response</h3>
                    <div class="flex items-center gap-4 text-sm">
                        <!-- Copy Button -->
                        <div class="relative">
                            <button
                                @click="navigator.clipboard.writeText($refs.responseBody.innerText); copied = true; setTimeout(() => copied = false, 2000)"
                                class="text-slate-400 hover:text-white flex items-center gap-1.5 px-2 py-1 hover:bg-slate-700 rounded transition-colors"
                            >
                                <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                <svg x-show="copied" style="display:none" class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span x-show="!copied">Copy</span>
                                <span x-show="copied" style="display:none" class="text-green-400 font-medium">Copied!</span>
                            </button>
                        </div>

                        <div class="h-4 w-px bg-slate-700"></div>

                        @if(isset($response['error']))
                            <span class="px-3 py-1 rounded-full bg-red-700/50 text-red-300 font-medium">Error</span>
                        @else
                            <span class="px-3 py-1 rounded-full font-medium {{ $response['status'] < 300 ? 'bg-green-700/50 text-green-300' : ($response['status'] < 400 ? 'bg-yellow-700/50 text-yellow-300' : 'bg-red-700/50 text-red-300') }}">
                                {{ $response['status'] }} {{ $response['status'] < 300 ? 'OK' : 'Error' }}
                            </span>
                            <span class="text-slate-400 font-mono text-xs hidden xl:inline-block">
                                <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $response['time'] }}ms
                            </span>
                            <span class="text-slate-400 font-mono text-xs hidden xl:inline-block">{{ number_format($response['size']) }} B</span>
                            @if(isset($response['timestamp']))
                                <div class="h-4 w-px bg-slate-700 mx-1"></div>
                                <span class="text-slate-500 text-xs" title="{{ $response['timestamp'] }}">
                                    {{ \Carbon\Carbon::parse($response['timestamp'])->diffForHumans() }}
                                </span>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="flex-1 overflow-auto p-2 bg-slate-900">
                    @if(isset($response['error']))
                        <div class="text-red-400">
                            <p class="font-medium mb-2 text-lg">Error</p>
                            <pre x-ref="responseBody" class="text-sm bg-red-950/50 p-2 rounded-lg border border-red-900 overflow-auto">{{ $response['message'] }}</pre>
                        </div>
                    @else
                        <pre x-ref="responseBody" class="text-sm text-slate-300 font-mono bg-slate-800 p-2 rounded-lg border border-slate-700 overflow-auto">{{ json_encode(json_decode($response['body']), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?? $response['body'] }}</pre>
                    @endif
                </div>
                @else
                <div class="flex-1 flex items-center justify-center">
                    <div class="text-center text-slate-500">
                        <div class="bg-slate-800/50 p-2 rounded-full inline-block mb-4">
                            <svg class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-lg font-medium mb-1 text-slate-300">Ready to test</p>
                        <p class="text-sm text-slate-500">Send a request to see the response here</p>
                    </div>
                </div>
                @endif
            </div>
            @else
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center text-slate-500">
                    <div class="bg-slate-800/50 p-2 rounded-full inline-block mb-4">
                         <svg class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-lg font-medium mb-1 text-slate-300">No request selected</p>
                    <p class="text-sm text-slate-500">Select a request from the sidebar to get started</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Autocomplete Dropdown Component -->
    <div
        x-data="EnvironmentAutocomplete.create()"
        x-init="init()"
        x-show="showAutocomplete"
        x-ref="autocompleteDropdown"
        class="autocomplete-dropdown"
        :style="`top: ${autocompletePosition.top}px; left: ${autocompletePosition.left}px;`"
        style="display: none;"
    >
        <div class="px-3 py-2 border-b border-slate-700 text-xs text-slate-400 font-semibold">
            Environment Variables ({{ $activeEnvironment ? $activeEnvironment->name : 'None' }})
        </div>
        <template x-if="filteredVariables.length === 0">
            <div class="px-3 py-4 text-sm text-slate-500 text-center">
                No variables available
            </div>
        </template>
        <template x-for="(variable, index) in filteredVariables" :key="variable.key">
            <div
                class="autocomplete-item"
                :class="{ 'selected': index === selectedIndex }"
                @click="selectVariableFromClick(variable)"
                @mouseenter="selectedIndex = index"
            >
                <div class="autocomplete-item-key" x-text="variable.formatted_key"></div>
                <div class="autocomplete-item-value" x-text="variable.value || 'No value'"></div>
                <div x-show="variable.description" class="text-xs text-slate-600 mt-1" x-text="variable.description"></div>
            </div>
        </template>
    </div>
</div>

@push('styles')
<style>
    /* Variable highlighting */
    .env-variable {
        color: #10b981; /* green */
        font-weight: 500;
    }

    /* Autocomplete dropdown */
    .autocomplete-dropdown {
        position: absolute;
        z-index: 1000;
        background: #1e293b;
        border: 1px solid #475569;
        border-radius: 0.375rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        max-height: 200px;
        overflow-y: auto;
        min-width: 250px;
    }

    .autocomplete-item {
        padding: 0.5rem 0.75rem;
        cursor: pointer;
        transition: background-color 0.15s;
    }

    .autocomplete-item:hover,
    .autocomplete-item.selected {
        background: #334155;
    }

    .autocomplete-item-key {
        font-family: 'Courier New', monospace;
        color: #60a5fa;
        font-size: 0.875rem;
    }

    .autocomplete-item-value {
        color: #94a3b8;
        font-size: 0.75rem;
        margin-top: 0.125rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Global autocomplete functionality
        window.EnvironmentAutocomplete = {
            create() {
                return {
                    showAutocomplete: false,
                    autocompletePosition: { top: 0, left: 0 },
                    filteredVariables: @js($this->getAvailableVariables()),
                    allVariables: @js($this->getAvailableVariables()),
                    selectedIndex: 0,
                    activeElement: null,
                    cursorPosition: 0,

                    init() {
                        // Listen for Livewire updates to refresh variables
                        Livewire.on('environmentChanged', () => {
                            this.refreshVariables();
                        });

                        this.refreshVariables();
                        this.setupKeyboardListeners();
                    },

                    refreshVariables() {
                        // Call Livewire method to get updated variables
                        @this.call('getAvailableVariables').then(vars => {
                            this.allVariables = vars;
                            this.filteredVariables = vars;
                        });
                    },

                    setupKeyboardListeners() {
                        document.addEventListener('keydown', (e) => {
                            // Check if Ctrl+Space is pressed
                            if (e.ctrlKey && e.key === ' ') {
                                e.preventDefault();
                                const activeEl = document.activeElement;

                                // Check if active element is an input or textarea
                                if (activeEl && (activeEl.tagName === 'INPUT' || activeEl.tagName === 'TEXTAREA')) {
                                    // Exclude certain inputs
                                    if (activeEl.type === 'checkbox' || activeEl.type === 'file') {
                                        return;
                                    }

                                    this.activeElement = activeEl;
                                    this.cursorPosition = activeEl.selectionStart;
                                    this.showAutocompleteDropdown(activeEl);
                                }
                            }

                            // Handle autocomplete navigation
                            if (this.showAutocomplete) {
                                if (e.key === 'ArrowDown') {
                                    e.preventDefault();
                                    this.selectedIndex = Math.min(this.selectedIndex + 1, this.filteredVariables.length - 1);
                                    this.scrollToSelected();
                                } else if (e.key === 'ArrowUp') {
                                    e.preventDefault();
                                    this.selectedIndex = Math.max(this.selectedIndex - 1, 0);
                                    this.scrollToSelected();
                                } else if (e.key === 'Enter' || e.key === 'Tab') {
                                    if (this.filteredVariables.length > 0) {
                                        e.preventDefault();
                                        this.insertVariable(this.filteredVariables[this.selectedIndex]);
                                    }
                                } else if (e.key === 'Escape') {
                                    this.closeAutocomplete();
                                }
                            }
                        });

                        // Close autocomplete when clicking outside
                        document.addEventListener('click', (e) => {
                            if (!e.target.closest('.autocomplete-dropdown') && this.showAutocomplete) {
                                this.closeAutocomplete();
                            }
                        });
                    },

                    showAutocompleteDropdown(element) {
                        if (this.allVariables.length === 0) {
                            console.log('No environment variables available');
                            return;
                        }

                        // Get cursor position in pixels
                        const rect = element.getBoundingClientRect();
                        const cursorPos = element.selectionStart;

                        // Calculate approximate pixel position of cursor
                        // This is a simple approximation
                        const textBeforeCursor = element.value.substring(0, cursorPos);
                        const lines = textBeforeCursor.split('\n');
                        const currentLineLength = lines[lines.length - 1].length;

                        // Position the dropdown
                        this.autocompletePosition = {
                            top: rect.top + rect.height + window.scrollY,
                            left: rect.left + (currentLineLength * 7) + window.scrollX // Approximate character width
                        };

                        this.filteredVariables = this.allVariables;
                        this.selectedIndex = 0;
                        this.showAutocomplete = true;
                    },

                    insertVariable(variable) {
                        if (!this.activeElement || !variable) return;

                        const element = this.activeElement;
                        const cursorPos = this.cursorPosition;
                        const currentValue = element.value || '';

                        // Use pre-formatted key from backend
                        const variableText = variable.formatted_key || ('{{' + variable.key + '}}');
                        const newValue = currentValue.substring(0, cursorPos) + variableText + currentValue.substring(cursorPos);

                        // Update the value
                        element.value = newValue;

                        // Trigger input event to update Livewire
                        element.dispatchEvent(new Event('input', { bubbles: true }));

                        // Set cursor position after the inserted variable
                        const newCursorPos = cursorPos + variableText.length;
                        element.setSelectionRange(newCursorPos, newCursorPos);

                        // Focus back on the element
                        element.focus();

                        this.closeAutocomplete();
                    },

                    formatVariableName(key) {
                        return '{{' + key + '}}';
                    },

                    selectVariableFromClick(variable) {
                        this.insertVariable(variable);
                    },

                    scrollToSelected() {
                        this.$nextTick(() => {
                            const dropdown = this.$refs.autocompleteDropdown;
                            if (dropdown) {
                                const selectedItem = dropdown.children[this.selectedIndex];
                                if (selectedItem) {
                                    selectedItem.scrollIntoView({ block: 'nearest' });
                                }
                            }
                        });
                    },

                    closeAutocomplete() {
                        this.showAutocomplete = false;
                        this.selectedIndex = 0;
                    }
                }
            }
        };
    });
</script>
@endpush
