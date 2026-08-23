@extends('layouts.public')

@section('title', 'My Favorites - NontonKu')
@section('meta_robots', 'noindex, nofollow')

@section('content')
<div class="py-12">
    <x-container>
        <nav aria-label="Breadcrumb" class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-zinc-400">
                <li><a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a></li>
                <li><span class="mx-1">/</span></li>
                <li><a href="{{ route('profile.edit') }}" class="hover:text-indigo-600 transition-colors">Profile</a></li>
                <li><span class="mx-1">/</span></li>
                <li aria-current="page" class="text-gray-900 dark:text-white font-medium">Favorites</li>
            </ol>
        </nav>

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                My Favorites
            </h1>
        </div>
        @if($favorites->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <svg class="h-16 w-16 text-gray-300 dark:text-zinc-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">No favorites yet</h3>
                <p class="mt-1 text-gray-500 dark:text-zinc-400">Add your favorite movies and series to access them quickly.</p>
                <div class="mt-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Explore Catalog
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                @foreach($favorites as $favorite)
                    <x-content-card :content="$favorite->content" />
                @endforeach
            </div>

            <div class="mt-8">
                {{ $favorites->links() }}
            </div>
        @endif
    </x-container>
</div>
@endsection
