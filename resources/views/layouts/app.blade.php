<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'API Runner' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-900">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-800 border-r border-slate-700 flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-slate-700">
                <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-blue-600 bg-clip-text text-transparent">
                    API Runner
                </h1>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="{{ route('dashboard') }}"
                   wire:navigate
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('workspaces') }}"
                   wire:navigate
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('workspaces*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    <span>Workspaces</span>
                </a>

                <a href="{{ route('api-runner') }}"
                   wire:navigate
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('api-runner*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>API Runner</span>
                </a>
            </nav>

            <!-- User Menu -->
            <div class="p-4 border-t border-slate-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-200">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 rounded-lg transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-slate-800 border-b border-slate-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-slate-100">
                        {{ $header ?? 'Dashboard' }}
                    </h2>

                    <!-- Notifications -->
                    <livewire:notifications />
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
