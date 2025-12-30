<div class="glass-effect p-8 rounded-2xl shadow-2xl">
    <!-- Logo/Title -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-blue-600 bg-clip-text text-transparent mb-2">
            API Runner
        </h1>
        <p class="text-slate-400">Create your account</p>
    </div>

    <!-- Register Form -->
    <form wire:submit="register" class="space-y-6">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
                Full Name
            </label>
            <input
                type="text"
                id="name"
                wire:model="name"
                class="input-dark w-full"
                placeholder="John Doe"
                autofocus
            >
            @error('name')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                Email Address
            </label>
            <input
                type="email"
                id="email"
                wire:model="email"
                class="input-dark w-full"
                placeholder="you@example.com"
            >
            @error('email')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
                Password
            </label>
            <input
                type="password"
                id="password"
                wire:model="password"
                class="input-dark w-full"
                placeholder="••••••••"
            >
            @error('password')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">
                Confirm Password
            </label>
            <input
                type="password"
                id="password_confirmation"
                wire:model="password_confirmation"
                class="input-dark w-full"
                placeholder="••••••••"
            >
        </div>

        <!-- Submit Button -->
        <button
            type="submit"
            class="btn-primary w-full"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>Create Account</span>
            <span wire:loading>Creating account...</span>
        </button>
    </form>

    <!-- Login Link -->
    <p class="mt-6 text-center text-sm text-slate-400">
        Already have an account?
        <a href="{{ route('login') }}" wire:navigate class="text-blue-400 hover:text-blue-300 font-medium">
            Sign in
        </a>
    </p>
</div>
