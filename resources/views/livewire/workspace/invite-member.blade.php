<div class="max-w-2xl mx-auto space-y-6">
    <!-- Workspace Info -->
    <div class="card-dark p-6">
        <h3 class="text-xl font-semibold text-slate-100 mb-2">{{ $workspace->name }}</h3>
        <p class="text-sm text-slate-400">Invite team members to collaborate on this workspace</p>
    </div>

    <!-- Invite Form -->
    <div class="card-dark p-6">
        <h4 class="font-semibold text-slate-100 mb-4">Send Invitation</h4>

        @if(session()->has('message'))
        <div class="mb-4 p-4 rounded-lg bg-green-500/10 border border-green-500/20">
            <p class="text-sm text-green-400">{{ session('message') }}</p>
        </div>
        @endif

        <form wire:submit="sendInvitation" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                    Email Address *
                </label>
                <input
                    type="email"
                    id="email"
                    wire:model="email"
                    class="input-dark w-full"
                    placeholder="colleague@example.com"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-slate-300 mb-2">
                    Role *
                </label>
                <select wire:model="role" id="role" class="input-dark w-full">
                    <option value="viewer">Viewer - Can view only</option>
                    <option value="member">Member - Can view and edit</option>
                    <option value="admin">Admin - Can manage workspace</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove>Send Invitation</span>
                    <span wire:loading>Sending...</span>
                </button>
                <a href="{{ route('workspaces.detail', $workspace) }}" wire:navigate class="btn-secondary">
                    Back to Workspace
                </a>
            </div>
        </form>
    </div>

    <!-- Pending Invitations -->
    @if($pendingInvitations->count() > 0)
    <div class="card-dark">
        <div class="p-6 border-b border-slate-700">
            <h4 class="font-semibold text-slate-100">Pending Invitations</h4>
        </div>
        <div class="divide-y divide-slate-700">
            @foreach($pendingInvitations as $invitation)
            <div class="p-6 flex items-center justify-between">
                <div>
                    <p class="font-medium text-slate-200">{{ $invitation->email }}</p>
                    <p class="text-sm text-slate-400">
                        Invited by {{ $invitation->inviter->name }} • {{ $invitation->created_at->diffForHumans() }}
                    </p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs bg-yellow-700/50 text-yellow-300">Pending</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
