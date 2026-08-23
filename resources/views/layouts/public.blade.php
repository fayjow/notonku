<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'NontonKu'))</title>
    <meta name="description" content="@yield('meta_description', 'NontonKu is a modern platform to watch your favorite movies, series, anime, and donghua.')">
    
    @if(View::hasSection('canonical'))
        <link rel="canonical" href="@yield('canonical')">
    @endif

    <!-- Open Graph placeholder -->
    <meta property="og:site_name" content="NontonKu">
    <meta property="og:title" content="@yield('title', config('app.name', 'NontonKu'))">
    <meta property="og:description" content="@yield('meta_description', 'NontonKu is a modern platform to watch your favorite movies, series, anime, and donghua.')">
    <meta property="og:type" content="website">
    
    @stack('meta')

    <!-- Prevent FOUC -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 dark:bg-gradient-to-br dark:from-zinc-950 dark:via-zinc-900 dark:to-black dark:text-zinc-100 min-h-screen flex flex-col selection:bg-indigo-500 selection:text-white motion-reduce:transition-none motion-reduce:transform-none">
    
    @include('layouts.public-navigation')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('layouts.footer')

</body>
</html>
