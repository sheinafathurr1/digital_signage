<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Digital Signage') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|fira-code:400&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-body text-ink antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12 bg-background">
            <div class="flex flex-col items-center text-center mb-8">
                <a href="/" class="flex items-center justify-center w-16 h-16 rounded-xl bg-primary/10 text-primary">
                    <x-application-logo class="w-9 h-9 fill-current" />
                </a>
                <h1 class="mt-4 text-2xl font-bold tracking-tight text-ink">{{ config('app.name', 'Digital Signage') }}</h1>
                <p class="mt-1 text-sm text-muted">Panel admin papan pengumuman digital</p>
            </div>

            <div class="w-full sm:max-w-md px-6 py-6 bg-surface border border-border shadow-card overflow-hidden rounded-lg">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-muted">&copy; {{ date('Y') }} {{ config('app.name', 'Digital Signage') }}</p>
        </div>
    </body>
</html>
