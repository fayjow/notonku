@extends('layouts.public')

@section('title', 'Browse Genres - NontonKu')

@section('content')
<div class="py-12">
    <x-container>
        <nav aria-label="Breadcrumb" class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-zinc-400">
                <li><a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a></li>
                <li><span class="mx-1">/</span></li>
                <li aria-current="page" class="text-gray-900 dark:text-white font-medium">Genres</li>
            </ol>
        </nav>

        <div class="mb-12 text-center max-w-2xl mx-auto">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-4">
                Explore Genres
            </h1>
            <p class="text-lg text-gray-600 dark:text-zinc-400">
                Discover the best movies and series across all categories.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($genres as $genre)
                <a href="{{ route('genres.show', $genre->slug) }}" class="group block relative rounded-2xl overflow-hidden border border-gray-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm hover:shadow-lg hover:border-indigo-500/50 dark:hover:border-indigo-500/50 hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-white dark:from-zinc-800 dark:to-zinc-900 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                {{ $genre->name }}
                            </h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-zinc-400">
                                {{ $genre->contents_count }} titles available
                            </p>
                        </div>
                        <div class="mt-6 self-end">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </x-container>
</div>
@endsection
