@extends('layouts.public')

@section('title', 'Search Results - NontonKu')

@section('content')
<div class="py-12">
    <x-container>
        <nav aria-label="Breadcrumb" class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-zinc-400">
                <li><a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a></li>
                <li><span class="mx-1">/</span></li>
                <li aria-current="page" class="text-gray-900 dark:text-white font-medium">Search</li>
            </ol>
        </nav>

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                Search Results @if($q) for "{{ $q }}" @endif
            </h1>
            <p class="text-gray-500 dark:text-zinc-400 mt-2">{{ $contents->total() }} results found.</p>
        </div>

        <form action="{{ route('search') }}" method="GET" class="mb-12 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl p-6 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4 items-end">
                <!-- Keyword -->
                <div class="lg:col-span-2">
                    <label for="q" class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Keyword</label>
                    <input type="text" name="q" id="q" value="{{ $q }}" class="block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Title...">
                </div>
                
                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Type</label>
                    <select name="type" id="type" class="block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">All Types</option>
                        <option value="movie" {{ $type === 'movie' ? 'selected' : '' }}>Movie</option>
                        <option value="series" {{ $type === 'series' ? 'selected' : '' }}>Series</option>
                        <option value="anime" {{ $type === 'anime' ? 'selected' : '' }}>Anime</option>
                        <option value="donghua" {{ $type === 'donghua' ? 'selected' : '' }}>Donghua</option>
                    </select>
                </div>
                
                <!-- Genre -->
                <div>
                    <label for="genre" class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Genre</label>
                    <select name="genre" id="genre" class="block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">All Genres</option>
                        @foreach($genres as $g)
                            <option value="{{ $g->slug }}" {{ $genreSlug === $g->slug ? 'selected' : '' }}>{{ $g->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Sort By</label>
                    <select name="sort" id="sort" class="block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Latest Release</option>
                        <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>Highest Rated</option>
                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="az" {{ $sort === 'az' ? 'selected' : '' }}>A-Z</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full flex justify-center items-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-zinc-900 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Filter
                    </button>
                </div>
            </div>
        </form>

        @if($contents->isEmpty())
            <div class="text-center py-20 bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No results found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">Try adjusting your search terms or filters.</p>
                <div class="mt-6">
                    <a href="{{ route('search') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500/10 dark:text-indigo-400 dark:hover:bg-indigo-500/20">
                        Clear Filters
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                @foreach($contents as $content)
                    <x-content-card :content="$content" />
                @endforeach
            </div>
            
            <div class="mt-12">
                {{ $contents->links() }}
            </div>
        @endif
    </x-container>
</div>
@endsection
