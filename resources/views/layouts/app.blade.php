<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|fira-code:400&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-body text-ink antialiased">
        <div class="min-h-screen bg-background">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-surface border-b border-border">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @php
            // These status codes are handled by their own pages (e.g. the
            // profile/password forms) rather than being ready-to-display
            // messages, so the generic toast below must skip them.
            $rawStatusCodes = ['profile-updated', 'password-updated', 'verification-link-sent'];
        @endphp
        @if (session('status') && ! in_array(session('status'), $rawStatusCodes, true))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: @js(session('status')),
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        iconColor: '#27AE60',
                    });
                });
            </script>
        @endif

        @stack('scripts')
    </body>
</html>
