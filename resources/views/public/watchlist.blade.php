@extends('layouts.public')

@section('title', 'My Watchlist - NontonKu')
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
                <li aria-current="page" class="text-gray-900 dark:text-white font-medium">Watchlist</li>
            </ol>
        </nav>

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                Episode Watchlist
            </h1>
        </div>
        @if($bookmarks->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <svg class="h-16 w-16 text-gray-300 dark:text-zinc-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Watchlist is empty</h3>
                <p class="mt-1 text-gray-500 dark:text-zinc-400">Bookmark episodes to watch them later.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($bookmarks as $bookmark)
                    @php
                        $episode = $bookmark->episode;
                        $content = $episode->season->content;
                        
                        $routeName = $content->type->value . 's.show';
                        if ($content->type->value === 'anime') $routeName = 'anime.show';
                        if ($content->type->value === 'donghua') $routeName = 'donghua.show';
                        if ($content->type->value === 'series') $routeName = 'series.show';
                    @endphp
                    <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-lg overflow-hidden shadow-sm flex flex-col relative group">
                        <a href="{{ route($routeName, $content->slug) }}#episodes" class="block aspect-video bg-gray-200 dark:bg-zinc-800 relative">
                            @if($episode->thumbnail_url)
                                <img src="{{ url($episode->thumbnail_url) }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            @endif
                            <div class="absolute bottom-2 right-2 bg-black/80 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">
                                EP {{ $episode->episode_number }}
                            </div>
                        </a>
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <h4 class="font-semibold text-sm text-gray-900 dark:text-white line-clamp-2" title="{{ $episode->title ?: 'Episode ' . $episode->episode_number }}">
                                    {{ $episode->title ?: 'Episode ' . $episode->episode_number }}
                                </h4>
                                <p class="mt-1 text-xs text-gray-500 dark:text-zinc-400">
                                    {{ $content->title }} &bull; Season {{ $episode->season->season_number }}
                                </p>
                            </div>
                            
                            <div class="mt-4 flex justify-between items-center"
                                x-data="{ 
                                    isBookmarked: true, 
                                    isLoading: false,
                                    toggle() {
                                        if (this.isLoading) return;
                                        this.isLoading = true;
                                        
                                        fetch('/watchlist/{{ $episode->id }}', {
                                            method: 'DELETE',
                                            headers: {
                                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                                'Accept': 'application/json'
                                            }
                                        }).then(() => {
                                            this.isBookmarked = false;
                                        }).finally(() => {
                                            this.isLoading = false;
                                        });
                                    }
                                }"
                                x-show="isBookmarked"
                            >
                                <a href="{{ route($routeName, $content->slug) }}#episodes" class="text-xs font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Go to content
                                </a>
                                
                                <button @@click.prevent="toggle" :disabled="isLoading" class="text-gray-400 hover:text-red-500 focus:outline-none transition-colors" aria-label="Remove from watchlist" title="Remove">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $bookmarks->links() }}
            </div>
        @endif
    </x-container>
</div>
@endsection
