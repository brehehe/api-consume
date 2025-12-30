<div class="max-w-2xl mx-auto">
    <div class="card-dark p-8">
        <form wire:submit="save" class="space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
                    Workspace Name *
                </label>
                <input
                    type="text"
                    id="name"
                    wire:model="name"
                    class="input-dark w-full"
                    placeholder="My Awesome API Project"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-slate-300 mb-2">
                    Description
                </label>
                <textarea
                    id="description"
                    wire:model="description"
                    class="input-dark w-full"
                    rows="3"
                    placeholder="What is this workspace for?"
                ></textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-3">
                    Workspace Type *
                </label>
                <div class="grid grid-cols-1 gap-3">
                    <!-- Private -->
                    <label class="flex items-start gap-3 p-4 rounded-lg border border-slate-700 cursor-pointer hover:border-slate-600 transition-colors {{ $type === 'private' ? 'border-blue-500 bg-blue-500/10' : '' }}">
                        <input
                            type="radio"
                            wire:model.live="type"
                            name="type"
                            value="private"
                            class="mt-1"
                        >
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span class="font-medium text-slate-200">Private</span>
                            </div>
                            <p class="text-sm text-slate-400">Only you can access this workspace</p>
                        </div>
                    </label>

                    <!-- Public -->
                    <label class="flex items-start gap-3 p-4 rounded-lg border border-slate-700 cursor-pointer hover:border-slate-600 transition-colors {{ $type === 'public' ? 'border-green-500 bg-green-500/10' : '' }}">
                        <input
                            type="radio"
                            wire:model.live="type"
                            name="type"
                            value="public"
                            class="mt-1"
                        >
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-medium text-slate-200">Public</span>
                            </div>
                            <p class="text-sm text-slate-400">Anyone can view this workspace</p>
                        </div>
                    </label>

                    <!-- Team -->
                    <label class="flex items-start gap-3 p-4 rounded-lg border border-slate-700 cursor-pointer hover:border-slate-600 transition-colors {{ $type === 'team' ? 'border-blue-500 bg-blue-500/10' : '' }}">
                        <input
                            type="radio"
                            wire:model.live="type"
                            name="type"
                            value="team"
                            class="mt-1"
                        >
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="font-medium text-slate-200">Team</span>
                            </div>
                            <p class="text-sm text-slate-400">Invite team members to collaborate</p>
                        </div>
                    </label>
                </div>
                @error('type')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $isEdit ? 'Update Workspace' : 'Create Workspace' }}</span>
                    <span wire:loading>Saving...</span>
                </button>
                <a href="{{ route('workspaces') }}" wire:navigate class="btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
