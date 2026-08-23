@extends('layouts.public')

@section('title', $genre->name . ' Movies and Series - NontonKu')

@section('content')
<div class="py-12">
    <x-container>
        <nav aria-label="Breadcrumb" class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-zinc-400">
                <li><a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a></li>
                <li><span class="mx-1">/</span></li>
                <li><a href="{{ route('genres.index') }}" class="hover:text-indigo-600 transition-colors">Genres</a></li>
                <li><span class="mx-1">/</span></li>
                <li aria-current="page" class="text-gray-900 dark:text-white font-medium">{{ $genre->name }}</li>
            </ol>
        </nav>

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white tracking-tight">
                    {{ $genre->name }}
                </h1>
                <p class="text-gray-500 dark:text-zinc-400 mt-2">{{ $contents->total() }} titles found in this genre.</p>
            </div>
            
            <form action="{{ route('genres.show', $genre->slug) }}" method="GET" class="w-full md:w-auto">
                <div class="flex items-center space-x-3">
                    <label for="sort" class="text-sm font-medium text-gray-700 dark:text-zinc-300 whitespace-nowrap">Sort by</label>
                    <select name="sort" id="sort" onchange="this.form.submit()" class="block w-full min-w-[160px] rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Latest Release</option>
                        <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>Highest Rated</option>
                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="az" {{ $sort === 'az' ? 'selected' : '' }}>A-Z</option>
                    </select>
                </div>
            </form>
        </div>

        @if($contents->isEmpty())
            <x-empty-state title="No content found" description="We don't have any titles for this genre yet." />
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
