<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-cloak
    x-data="{currentTheme: localStorage.getItem('theme') || localStorage.setItem('theme', 'system')}"
    x-init="$watch('currentTheme', val => localStorage.setItem('theme', val))"
    x-bind:class="{'dark': currentTheme === 'dark' || (currentTheme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)}"
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Passive Guidebook') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @filamentStyles
    </head>
    <body class="font-sans antialiased">
        @if (\Illuminate\Support\Facades\Auth::user()->onTrial())
            <div class="bg-blue-50 dark:bg-blue-300 p-4">
                <div class="max-w-7xl mx-auto text-center text-sm font-bold text-blue-700">
                    <p>You are currently on a trial period. Your trial ends on {{ resolve(\App\Helpers\TimezoneHelper::class)->convertToLocal(date: \Illuminate\Support\Facades\Auth::user()->trial_ends_at, format: 'l \t\h\e jS \o\f F, Y') }}. <a href="/billing" class="underline hover:no-underline">Click here to buy a membership!</a></p>
                </div>
            </div>
        @endif

        <x-banner />

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @filamentScripts
        @livewire('notifications')
        <x-impersonate::banner/>
    </body>
</html>
