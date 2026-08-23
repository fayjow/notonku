@extends('layouts.public')

@section('title', 'NontonKu - Home')
@section('meta_description', 'Discover the best movies, series, anime, and donghua on NontonKu.')

@section('content')
@if($heroContent->isNotEmpty())
    @php
        $hero = $heroContent->first();
        $heroRouteName = $hero->type->value . 's.show';
        if ($hero->type->value === 'anime') $heroRouteName = 'anime.show';
        if ($hero->type->value === 'donghua') $heroRouteName = 'donghua.show';
        if ($hero->type->value === 'series') $heroRouteName = 'series.show';
    @endphp
    <!-- Hero Section -->
    <div class="relative w-full h-[75vh] min-h-[600px] overflow-hidden bg-black flex items-center">
        <!-- Background Image with Gradients -->
        <div class="absolute inset-0 w-full h-full">
            @if($hero->backdrop_url)
                <img src="{{ $hero->backdrop_url }}" alt="{{ $hero->title }}" class="w-full h-full object-cover object-top opacity-70">
            @else
                <div class="w-full h-full bg-zinc-900"></div>
            @endif
            
            <!-- Vignette and Fade Gradients -->
            <div class="absolute inset-0 bg-gradient-to-t from-gray-50 via-transparent to-transparent dark:from-zinc-950 dark:via-zinc-950/20"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-gray-50/90 via-transparent to-transparent dark:from-zinc-950 dark:via-transparent"></div>
        </div>

        <x-container class="relative z-10 w-full">
            <div class="max-w-2xl space-y-4 lg:space-y-6">
                <!-- Type & Year -->
                <div class="flex items-center space-x-3 text-sm font-semibold tracking-wider text-indigo-500 uppercase">
                    <span>{{ $hero->type->value }}</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500/50"></span>
                    <span>{{ $hero->release_date ? substr($hero->release_date, 0, 4) : 'N/A' }}</span>
                    @if($hero->age_rating)
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500/50"></span>
                        <span class="border border-indigo-500/30 px-1.5 rounded">{{ $hero->age_rating }}</span>
                    @endif
                </div>

                <!-- Title -->
                <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold text-gray-900 dark:text-white leading-tight drop-shadow-lg">
                    {{ $hero->title }}
                </h1>
                
                <!-- Metadata Row -->
                <div class="flex items-center space-x-4 text-sm font-medium text-gray-700 dark:text-zinc-300">
                    @if($hero->average_rating > 0)
                        <div class="flex items-center text-yellow-500">
                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span class="text-base text-gray-900 dark:text-white">{{ number_format($hero->average_rating, 1) }}</span>
                        </div>
                    @endif
                    @if($hero->duration_minutes)
                        <span>{{ intdiv($hero->duration_minutes, 60) > 0 ? intdiv($hero->duration_minutes, 60) . 'h ' : '' }}{{ $hero->duration_minutes % 60 }}m</span>
                    @endif
                    @if($hero->genres->isNotEmpty())
                        <div class="hidden sm:flex items-center space-x-2">
                            <span>•</span>
                            <span>{{ $hero->genres->take(3)->pluck('name')->join(', ') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Description -->
                <p class="text-base lg:text-lg text-gray-600 dark:text-zinc-300 line-clamp-3 max-w-xl">
                    {{ $hero->description }}
                </p>

                <!-- Actions -->
                <div class="pt-4 flex flex-wrap items-center gap-4">
                    <a href="{{ route($heroRouteName, $hero->slug) }}" class="inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-white transition-all bg-indigo-600 rounded-lg hover:bg-indigo-500 hover:shadow-lg hover:shadow-indigo-500/30 hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
                        Play Now
                    </a>
                </div>
            </div>
        </x-container>
    </div>
@endif

<div class="py-12 space-y-12">
    <x-container>
        @auth
            @if(isset($continueWatching) && $continueWatching->isNotEmpty())
                <!-- Continue Watching -->
                <x-section-heading title="Continue Watching" actionText="View all history" actionUrl="{{ route('history.index') }}" />
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    @foreach($continueWatching as $history)
                        <div class="flex items-center bg-white dark:bg-zinc-900 rounded-lg shadow border border-gray-200 dark:border-zinc-800 p-4">
                            <div class="flex-shrink-0 h-24 w-16 bg-gray-200 dark:bg-zinc-800 rounded overflow-hidden relative">
                                @if($history->content->poster_url)
                                    <img src="{{ $history->content->poster_url }}" alt="{{ $history->content->title }}" class="h-full w-full object-cover">
                                @endif
                                <div class="absolute bottom-0 inset-x-0 h-1 bg-gray-200/50">
                                    @php
                                        $percentage = $history->duration_seconds > 0 ? min(100, round(($history->progress_seconds / $history->duration_seconds) * 100)) : 0;
                                    @endphp
                                    <div class="bg-indigo-500 h-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                @php
                                    $routeName = $history->content->type->value . 's.show';
                                    if ($history->content->type->value === 'anime') $routeName = 'anime.show';
                                    if ($history->content->type->value === 'donghua') $routeName = 'donghua.show';
                                    if ($history->content->type->value === 'series') $routeName = 'series.show';
                                @endphp
                                <h4 class="text-base font-medium text-gray-900 dark:text-white line-clamp-1">
                                    <a href="{{ route($routeName, $history->content->slug) }}" class="hover:text-indigo-600">
                                        {{ $history->content->title }}
                                    </a>
                                </h4>
                                
                                @if($history->episode)
                                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5 line-clamp-1">
                                        S{{ $history->episode->season->season_number ?? 1 }} E{{ $history->episode->episode_number }}: {{ $history->episode->title }}
                                    </p>
                                @endif
                                
                                <div class="mt-3 flex items-center gap-2">
                                    <a href="{{ route($routeName, $history->content->slug) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        Resume
                                    </a>
                                    <!-- Remove button -->
                                    <form action="{{ route('history.destroy', $history->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-500 hover:text-red-500 transition-colors">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(isset($recommendations) && $recommendations->isNotEmpty())
                <!-- Recommendations -->
                <div class="mb-12">
                    <x-section-heading title="Recommended For You" />
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                        @foreach($recommendations as $content)
                            <x-content-card :content="$content" />
                        @endforeach
                    </div>
                </div>
            @endif

            @if(isset($becauseYouWatched) && $becauseYouWatched->isNotEmpty() && $lastWatched)
                <!-- Because You Watched -->
                <div class="mb-12">
                    <x-section-heading title="Because You Watched {{ $lastWatched->title }}" />
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                        @foreach($becauseYouWatched as $content)
                            <x-content-card :content="$content" />
                        @endforeach
                    </div>
                </div>
            @endif
        @endauth
        
        @guest
            @if(isset($recommendations) && $recommendations->isNotEmpty())
                <div class="mb-12">
                    <x-section-heading title="Trending Now" />
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                        @foreach($recommendations as $content)
                            <x-content-card :content="$content" />
                        @endforeach
                    </div>
                </div>
            @endif
        @endguest

        <!-- Popular -->
        @if(isset($popular) && $popular->isNotEmpty())
            <div class="mb-12">
                <x-section-heading title="Most Popular" />
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                    @foreach($popular as $content)
                        <x-content-card :content="$content" />
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Latest Movies -->
        @if(isset($latestMovies) && $latestMovies->isNotEmpty())
            <div class="mb-12">
                <x-section-heading title="Latest Movies" actionText="Explore movies" actionUrl="{{ route('movies') }}" />
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                    @foreach($latestMovies as $content)
                        <x-content-card :content="$content" />
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Latest Series -->
        @if(isset($latestSeries) && $latestSeries->isNotEmpty())
            <div class="mb-12">
                <x-section-heading title="Latest Series" actionText="Explore series" actionUrl="{{ route('series') }}" />
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                    @foreach($latestSeries as $content)
                        <x-content-card :content="$content" />
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Latest Anime -->
        @if(isset($latestAnime) && $latestAnime->isNotEmpty())
            <div class="mb-12">
                <x-section-heading title="Latest Anime" actionText="Explore anime" actionUrl="{{ route('anime') }}" />
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                    @foreach($latestAnime as $content)
                        <x-content-card :content="$content" />
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Latest Donghua -->
        @if(isset($latestDonghua) && $latestDonghua->isNotEmpty())
            <div class="mb-12">
                <x-section-heading title="Latest Donghua" actionText="Explore donghua" actionUrl="{{ route('donghua') }}" />
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                    @foreach($latestDonghua as $content)
                        <x-content-card :content="$content" />
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recently Added -->
        @if(isset($recentlyAdded) && $recentlyAdded->isNotEmpty())
            <div class="mb-12">
                <x-section-heading title="Recently Added" />
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                    @foreach($recentlyAdded->take(6) as $content)
                        <x-content-card :content="$content" />
                    @endforeach
                </div>
            </div>
        @endif
    </x-container>
</div>
@endsection
