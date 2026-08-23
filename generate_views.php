<?php

$views = [
    // --- Layouts ---
    'layouts/public.blade.php' => <<<'EOT'
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'NontonKu'))</title>
    <meta name="description" content="@yield('meta_description', 'NontonKu is a modern platform to watch your favorite movies, series, anime, and donghua.')">
    
    @hasSection('canonical')
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
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 dark:bg-zinc-950 dark:text-zinc-100 min-h-screen flex flex-col selection:bg-indigo-500 selection:text-white motion-reduce:transition-none motion-reduce:transform-none">
    
    @include('layouts.public-navigation')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('layouts.footer')

</body>
</html>
EOT,

    'layouts/public-navigation.blade.php' => <<<'EOT'
<nav x-data="{ open: false }" class="bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-800 sticky top-0 z-50">
    <x-container>
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center font-bold text-xl tracking-tight text-indigo-600 dark:text-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded px-1">
                    NontonKu
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden sm:-my-px sm:ml-8 sm:flex sm:space-x-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('home') ? 'border-indigo-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-200 hover:border-gray-300 dark:hover:border-zinc-600' }}">Home</a>
                    <a href="{{ route('movies') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('movies') ? 'border-indigo-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-200 hover:border-gray-300 dark:hover:border-zinc-600' }}">Movies</a>
                    <a href="{{ route('series') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('series') ? 'border-indigo-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-200 hover:border-gray-300 dark:hover:border-zinc-600' }}">Series</a>
                    <a href="{{ route('anime') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('anime') ? 'border-indigo-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-200 hover:border-gray-300 dark:hover:border-zinc-600' }}">Anime</a>
                    <a href="{{ route('donghua') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('donghua') ? 'border-indigo-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-200 hover:border-gray-300 dark:hover:border-zinc-600' }}">Donghua</a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <!-- Search Placeholder -->
                <div class="relative text-gray-400 focus-within:text-gray-600 dark:focus-within:text-zinc-300">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" class="block w-full rounded-md border-0 py-1.5 pl-10 pr-3 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-zinc-700 placeholder:text-gray-400 dark:placeholder:text-zinc-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:bg-zinc-800 sm:text-sm sm:leading-6" placeholder="Search..." aria-label="Search">
                </div>

                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" type="button" aria-label="Toggle Dark Mode" class="text-gray-500 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg text-sm p-2 transition-colors">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg x-cloak x-show="darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4.22 4.22a1 1 0 011.415 0l.708.708a1 1 0 01-1.414 1.414l-.708-.708a1 1 0 010-1.415zm3.78 3.78a1 1 0 010 1.414v1a1 1 0 11-1.414 0v-1a1 1 0 011.414-1.414zM10 15a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm-4.22-4.22a1 1 0 010 1.415l-.708.708a1 1 0 11-1.414-1.414l.708-.708a1 1 0 011.414 0zM3 10a1 1 0 011-1h1a1 1 0 110 2H4a1 1 0 01-1-1zM5.78 5.78a1 1 0 010-1.414l.708-.708a1 1 0 111.414 1.414l-.708.708a1 1 0 01-1.414 0zM10 6a4 4 0 100 8 4 4 0 000-8z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                </button>

                <!-- Auth Links -->
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-zinc-400 bg-white dark:bg-zinc-800 hover:text-gray-700 dark:hover:text-zinc-200 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            @if(Auth::user()->role === 'admin')
                                <x-dropdown-link :href="route('admin.dashboard')">Admin Dashboard</x-dropdown-link>
                            @endif
                            <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 dark:text-zinc-300 hover:text-indigo-600 dark:hover:text-indigo-400">Log in</a>
                    <a href="{{ route('register') }}" class="ml-4 inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition-colors">Register</a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="-mr-2 flex items-center sm:hidden space-x-2">
                <button @click="darkMode = !darkMode" type="button" aria-label="Toggle Dark Mode" class="text-gray-500 dark:text-zinc-400 p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg x-cloak x-show="darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4.22 4.22a1 1 0 011.415 0l.708.708a1 1 0 01-1.414 1.414l-.708-.708a1 1 0 010-1.415zm3.78 3.78a1 1 0 010 1.414v1a1 1 0 11-1.414 0v-1a1 1 0 011.414-1.414zM10 15a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm-4.22-4.22a1 1 0 010 1.415l-.708.708a1 1 0 11-1.414-1.414l.708-.708a1 1 0 011.414 0zM3 10a1 1 0 011-1h1a1 1 0 110 2H4a1 1 0 01-1-1zM5.78 5.78a1 1 0 010-1.414l.708-.708a1 1 0 111.414 1.414l-.708.708a1 1 0 01-1.414 0zM10 6a4 4 0 100 8 4 4 0 000-8z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                </button>
                <button @click="open = ! open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-zinc-500 hover:text-gray-500 dark:hover:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-colors" aria-expanded="false" aria-controls="mobile-menu">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" :class="{'hidden': open, 'block': ! open }" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6 hidden" :class="{'block': open, 'hidden': ! open }" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </x-container>

    <!-- Mobile menu -->
    <div x-show="open" id="mobile-menu" class="sm:hidden border-t border-gray-200 dark:border-zinc-800 bg-white dark:bg-zinc-900" style="display: none;">
        <div class="space-y-1 pb-3 pt-2">
            <a href="{{ route('home') }}" class="block border-l-4 py-2 pl-3 pr-4 text-base font-medium {{ request()->routeIs('home') ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:bg-zinc-800 dark:text-indigo-400' : 'border-transparent text-gray-600 dark:text-zinc-400 hover:border-gray-300 dark:hover:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-800 hover:text-gray-800 dark:hover:text-zinc-200' }}">Home</a>
            <a href="{{ route('movies') }}" class="block border-l-4 py-2 pl-3 pr-4 text-base font-medium {{ request()->routeIs('movies') ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:bg-zinc-800 dark:text-indigo-400' : 'border-transparent text-gray-600 dark:text-zinc-400 hover:border-gray-300 dark:hover:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-800 hover:text-gray-800 dark:hover:text-zinc-200' }}">Movies</a>
            <a href="{{ route('series') }}" class="block border-l-4 py-2 pl-3 pr-4 text-base font-medium {{ request()->routeIs('series') ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:bg-zinc-800 dark:text-indigo-400' : 'border-transparent text-gray-600 dark:text-zinc-400 hover:border-gray-300 dark:hover:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-800 hover:text-gray-800 dark:hover:text-zinc-200' }}">Series</a>
            <a href="{{ route('anime') }}" class="block border-l-4 py-2 pl-3 pr-4 text-base font-medium {{ request()->routeIs('anime') ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:bg-zinc-800 dark:text-indigo-400' : 'border-transparent text-gray-600 dark:text-zinc-400 hover:border-gray-300 dark:hover:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-800 hover:text-gray-800 dark:hover:text-zinc-200' }}">Anime</a>
            <a href="{{ route('donghua') }}" class="block border-l-4 py-2 pl-3 pr-4 text-base font-medium {{ request()->routeIs('donghua') ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:bg-zinc-800 dark:text-indigo-400' : 'border-transparent text-gray-600 dark:text-zinc-400 hover:border-gray-300 dark:hover:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-800 hover:text-gray-800 dark:hover:text-zinc-200' }}">Donghua</a>
        </div>
        
        <div class="border-t border-gray-200 dark:border-zinc-800 pb-4 pt-4">
            <div class="px-4">
                <div class="relative text-gray-400 focus-within:text-gray-600 dark:focus-within:text-zinc-300">
                    <input type="text" name="search-mobile" id="search-mobile" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-zinc-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:bg-zinc-800 sm:text-sm sm:leading-6" placeholder="Search...">
                </div>
            </div>
            
            <div class="mt-4 px-4 flex flex-col gap-2">
                @auth
                    <div class="text-base font-medium text-gray-800 dark:text-white">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">{{ Auth::user()->email }}</div>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="mt-3 text-indigo-600 dark:text-indigo-400 font-medium">Admin Dashboard</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="mt-3 text-gray-600 dark:text-zinc-300 font-medium hover:text-indigo-600">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="text-gray-600 dark:text-zinc-300 font-medium hover:text-indigo-600">Log Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-base font-medium text-gray-700 dark:text-zinc-300">Log in</a>
                    <a href="{{ route('register') }}" class="text-base font-medium text-indigo-600 dark:text-indigo-400">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
EOT,

    'layouts/footer.blade.php' => <<<'EOT'
<footer class="bg-white dark:bg-zinc-900 border-t border-gray-200 dark:border-zinc-800 mt-auto py-8">
    <x-container>
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex justify-center md:justify-start mb-6 md:mb-0">
                <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400 tracking-tight">NontonKu</span>
                <span class="ml-2 text-sm text-gray-500 dark:text-zinc-400 self-center">A modern streaming platform</span>
            </div>
            
            <div class="flex flex-wrap justify-center space-x-6 text-sm text-gray-500 dark:text-zinc-400">
                <a href="{{ route('home') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">Home</a>
                <a href="{{ route('movies') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">Movies</a>
                <a href="{{ route('series') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">Series</a>
                <a href="{{ route('anime') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">Anime</a>
            </div>
        </div>
        
        <div class="mt-8 border-t border-gray-200 dark:border-zinc-800 pt-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <p class="mt-8 text-base text-gray-400 md:mt-0 text-center md:text-left">
                &copy; {{ date('Y') }} NontonKu. All rights reserved.
            </p>
        </div>
    </x-container>
</footer>
EOT,

    // --- Components ---
    'components/container.blade.php' => <<<'EOT'
<div {{ $attributes->merge(['class' => 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full']) }}>
    {{ $slot }}
</div>
EOT,

    'components/card.blade.php' => <<<'EOT'
@props(['content'])

<a href="#" class="group block relative rounded-xl overflow-hidden bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-950">
    <div class="aspect-[2/3] bg-gray-200 dark:bg-zinc-800 relative overflow-hidden">
        @if(isset($content['poster_path']) && $content['poster_path'])
            <img src="{{ $content['poster_path'] }}" alt="{{ $content['title'] }}" class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105" loading="lazy">
        @else
            <div class="flex items-center justify-center w-full h-full text-gray-400 dark:text-zinc-600">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif

        @if(isset($content['average_rating']) && $content['average_rating'] > 0)
            <div class="absolute top-2 right-2 bg-black/70 backdrop-blur-sm text-white text-xs font-bold px-2 py-1 rounded flex items-center shadow-sm">
                <svg class="w-3 h-3 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                {{ number_format($content['average_rating'], 1) }}
            </div>
        @endif
        
        <!-- Placeholder Favorite Button -->
        <button type="button" aria-label="Add to favorites" class="absolute top-2 left-2 p-1.5 rounded-full bg-black/40 text-white hover:bg-black/70 transition-colors focus:outline-none focus:ring-2 focus:ring-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
        </button>
    </div>
    <div class="p-4">
        <h3 class="font-bold text-gray-900 dark:text-white truncate text-base leading-tight" title="{{ $content['title'] }}">
            {{ $content['title'] }}
        </h3>
        <div class="mt-1 flex items-center justify-between text-xs text-gray-500 dark:text-zinc-400">
            <span class="truncate pr-2">
                {{ isset($content['release_date']) ? substr($content['release_date'], 0, 4) : 'N/A' }} 
                &bull; <span class="capitalize">{{ $content['type'] ?? 'Unknown' }}</span>
            </span>
            @if(isset($content['age_rating']))
                <span class="inline-block px-1.5 border border-gray-300 dark:border-zinc-700 rounded text-[10px] font-semibold tracking-wider flex-shrink-0">
                    {{ $content['age_rating'] }}
                </span>
            @endif
        </div>
    </div>
</a>
EOT,

    'components/badge.blade.php' => <<<'EOT'
@props(['type' => 'default'])

@php
$classes = match($type) {
    'primary' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800',
    'success' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800',
    'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800',
    'danger' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800',
    default => 'bg-gray-100 text-gray-800 dark:bg-zinc-800 dark:text-zinc-300 border border-gray-200 dark:border-zinc-700',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium $classes"]) }}>
    {{ $slot }}
</span>
EOT,

    'components/alert.blade.php' => <<<'EOT'
@props(['type' => 'info'])

@php
$classes = match($type) {
    'success' => 'bg-green-50 text-green-800 dark:bg-green-900/20 dark:text-green-400 border-green-200 dark:border-green-900/50',
    'warning' => 'bg-yellow-50 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400 border-yellow-200 dark:border-yellow-900/50',
    'danger' => 'bg-red-50 text-red-800 dark:bg-red-900/20 dark:text-red-400 border-red-200 dark:border-red-900/50',
    default => 'bg-indigo-50 text-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-400 border-indigo-200 dark:border-indigo-900/50',
};
@endphp

<div {{ $attributes->merge(['class' => "p-4 rounded-lg border text-sm $classes"]) }} role="alert">
    {{ $slot }}
</div>
EOT,

    'components/section-heading.blade.php' => <<<'EOT'
@props(['title', 'actionText' => null, 'actionUrl' => null])

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $title }}</h2>
    @if($actionText && $actionUrl)
        <a href="{{ $actionUrl }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 focus:outline-none focus:underline transition-colors">
            {{ $actionText }} <span aria-hidden="true">&rarr;</span>
        </a>
    @endif
</div>
EOT,

    'components/empty-state.blade.php' => <<<'EOT'
@props(['title', 'description' => null])

<div class="text-center py-12 px-4 border-2 border-dashed border-gray-300 dark:border-zinc-800 rounded-xl">
    <div class="mx-auto w-12 h-12 text-gray-400 dark:text-zinc-600 mb-4">
        {{ $icon ?? '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>' }}
    </div>
    <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
    @if($description)
        <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">{{ $description }}</p>
    @endif
    <div class="mt-6">
        {{ $slot }}
    </div>
</div>
EOT,

    // --- Pages ---
    'public/home.blade.php' => <<<'EOT'
@extends('layouts.public')

@section('title', 'NontonKu - Home')
@section('meta_description', 'Discover the best movies, series, anime, and donghua on NontonKu.')

@section('content')
<div class="py-12">
    <x-container>
        <x-section-heading title="Trending Now" actionText="View all" actionUrl="#" />
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
            @foreach($trending ?? [] as $content)
                <x-card :content="$content" />
            @endforeach
        </div>

        <div class="mt-16">
            <x-section-heading title="Latest Series" actionText="Explore series" actionUrl="{{ route('series') }}" />
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                @foreach($series ?? [] as $content)
                    <x-card :content="$content" />
                @endforeach
            </div>
        </div>
    </x-container>
</div>
@endsection
EOT,

    'public/movies.blade.php' => <<<'EOT'
@extends('layouts.public')
@section('title', 'Movies - NontonKu')
@section('content')
<div class="py-12">
    <x-container>
        <x-section-heading title="All Movies" />
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
            @forelse($contents ?? [] as $content)
                <x-card :content="$content" />
            @empty
                <div class="col-span-full">
                    <x-empty-state title="No movies found" description="We couldn't find any movies at this time." />
                </div>
            @endforelse
        </div>
    </x-container>
</div>
@endsection
EOT,

    'public/series.blade.php' => <<<'EOT'
@extends('layouts.public')
@section('title', 'Series - NontonKu')
@section('content')
<div class="py-12">
    <x-container>
        <x-section-heading title="All Series" />
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
            @forelse($contents ?? [] as $content)
                <x-card :content="$content" />
            @empty
                <div class="col-span-full">
                    <x-empty-state title="No series found" description="We couldn't find any series at this time." />
                </div>
            @endforelse
        </div>
    </x-container>
</div>
@endsection
EOT,

    'public/anime.blade.php' => <<<'EOT'
@extends('layouts.public')
@section('title', 'Anime - NontonKu')
@section('content')
<div class="py-12">
    <x-container>
        <x-section-heading title="All Anime" />
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
            @forelse($contents ?? [] as $content)
                <x-card :content="$content" />
            @empty
                <div class="col-span-full">
                    <x-empty-state title="No anime found" description="We couldn't find any anime at this time." />
                </div>
            @endforelse
        </div>
    </x-container>
</div>
@endsection
EOT,

    'public/donghua.blade.php' => <<<'EOT'
@extends('layouts.public')
@section('title', 'Donghua - NontonKu')
@section('content')
<div class="py-12">
    <x-container>
        <x-section-heading title="All Donghua" />
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
            @forelse($contents ?? [] as $content)
                <x-card :content="$content" />
            @empty
                <div class="col-span-full">
                    <x-empty-state title="No donghua found" description="We couldn't find any donghua at this time." />
                </div>
            @endforelse
        </div>
    </x-container>
</div>
@endsection
EOT,
];

foreach ($views as $path => $content) {
    $fullPath = __DIR__ . '/resources/views/' . $path;
    @mkdir(dirname($fullPath), 0777, true);
    file_put_contents($fullPath, $content);
}

echo "Views generated successfully.\n";
