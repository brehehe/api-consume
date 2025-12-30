<div class="glass-effect p-8 rounded-2xl shadow-2xl">
    <!-- Logo/Title -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-blue-600 bg-clip-text text-transparent mb-2">
            API Runner
        </h1>
        <p class="text-slate-400">Sign in to your account</p>
    </div>

    <!-- Login Form -->
    <form wire:submit="login" class="space-y-6">
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
                autofocus
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

        <!-- Remember Me -->
        <div class="flex items-center">
            <input
                type="checkbox"
                id="remember"
                wire:model="remember"
                class="w-4 h-4 rounded border-slate-700 bg-slate-800 text-blue-600 focus:ring-blue-500 focus:ring-offset-slate-900"
            >
            <label for="remember" class="ml-2 text-sm text-slate-300">
                Remember me
            </label>
        </div>

        <!-- Submit Button -->
        <button
            type="submit"
            class="btn-primary w-full"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>Sign In</span>
            <span wire:loading>Signing in...</span>
        </button>
    </form>

    <!-- Register Link -->
    <p class="mt-6 text-center text-sm text-slate-400">
        Don't have an account?
        <a href="{{ route('register') }}" wire:navigate class="text-blue-400 hover:text-blue-300 font-medium">
            Sign up
        </a>
    </p>
</div>
