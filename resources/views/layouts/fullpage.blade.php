<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'API Runner' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-900">
    <div class="h-screen flex flex-col overflow-hidden">
        {{ $slot }}
    </div>
    @stack('scripts')
    @livewireScripts
</body>
</html>
