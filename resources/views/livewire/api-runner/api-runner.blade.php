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
</div>
