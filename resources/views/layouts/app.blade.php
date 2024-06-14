<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="font-sans antialiased bg-gray-200">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')
        <main class="m2 p-8 w-full">
            {{ $slot }}
        </main>
    </div>
    @livewire('notifications')
    @filamentScripts
    {{-- @vite('resources/js/app.js') --}}
</body>

</html>
