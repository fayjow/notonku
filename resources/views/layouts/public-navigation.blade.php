<nav x-data="{ open: false }" class="bg-white/80 dark:bg-zinc-950/80 backdrop-blur-md border-b border-gray-200/50 dark:border-white/5 sticky top-0 z-50">
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
                <!-- Search Form -->
                <form action="{{ route('search') }}" method="GET" class="relative text-gray-400 focus-within:text-gray-600 dark:focus-within:text-zinc-300">
                    <button type="submit" class="absolute inset-y-0 left-0 flex items-center pl-3 hover:text-indigo-500 focus:outline-none">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <input type="text" name="q" value="{{ request('q') }}" class="block w-full rounded-md border-0 py-1.5 pl-10 pr-3 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-zinc-700 placeholder:text-gray-400 dark:placeholder:text-zinc-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:bg-zinc-800 sm:text-sm sm:leading-6" placeholder="Search..." aria-label="Search">
                </form>

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
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('favorites.index')">
                                {{ __('Favorites') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('watchlist.index')">
                                {{ __('Watchlist') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('history.index')">
                                {{ __('History') }}
                            </x-dropdown-link>
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
                <!-- Mobile Dark Mode Toggle -->
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
                <form action="{{ route('search') }}" method="GET" class="relative text-gray-400 focus-within:text-gray-600 dark:focus-within:text-zinc-300 flex">
                    <input type="text" name="q" value="{{ request('q') }}" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-zinc-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:bg-zinc-800 sm:text-sm sm:leading-6" placeholder="Search...">
                    <button type="submit" class="ml-2 inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </form>
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
