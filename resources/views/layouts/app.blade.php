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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-950 text-gray-100">

    <div class="min-h-screen bg-gray-950">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-gray-900 shadow shadow-blue-500/20 border-b border-blue-800/30">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-blue-400 font-semibold">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="px-4 sm:px-6 lg:px-8 py-6">
            <div
                class="bg-gray-900/60 backdrop-blur-lg border border-blue-700/30 shadow-lg shadow-blue-900/30 rounded-xl p-6">
                {{ $slot }}
            </div>
        </main>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>