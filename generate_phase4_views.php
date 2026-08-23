<?php
$views = [
    'components/content-card.blade.php' => <<<'EOT'
@props(['content'])

@php
    // To support generating proper routes based on type
    $routeName = $content->type->value . 's.show';
    if ($content->type->value === 'anime') $routeName = 'anime.show'; // anime is singular/plural
    if ($content->type->value === 'donghua') $routeName = 'donghua.show';
@endphp

<a href="{{ route($routeName, $content->slug) }}" class="group block relative rounded-xl overflow-hidden bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-950">
    <div class="aspect-[2/3] bg-gray-200 dark:bg-zinc-800 relative overflow-hidden">
        @if($content->poster_path)
            <img src="{{ $content->poster_path }}" alt="{{ $content->title }}" class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105" loading="lazy">
        @else
            <div class="flex items-center justify-center w-full h-full text-gray-400 dark:text-zinc-600">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif

        @if($content->average_rating > 0)
            <div class="absolute top-2 right-2 bg-black/70 backdrop-blur-sm text-white text-xs font-bold px-2 py-1 rounded flex items-center shadow-sm">
                <svg class="w-3 h-3 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                {{ number_format($content->average_rating, 1) }}
            </div>
        @endif
        
        <!-- Placeholder Favorite Button -->
        <button type="button" aria-label="Add to favorites" class="absolute top-2 left-2 p-1.5 rounded-full bg-black/40 text-white hover:bg-black/70 transition-colors focus:outline-none focus:ring-2 focus:ring-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
        </button>
    </div>
    <div class="p-4">
        <h3 class="font-bold text-gray-900 dark:text-white truncate text-base leading-tight" title="{{ $content->title }}">
            {{ $content->title }}
        </h3>
        <div class="mt-1 flex items-center justify-between text-xs text-gray-500 dark:text-zinc-400">
            <span class="truncate pr-2">
                {{ $content->release_date ? substr($content->release_date, 0, 4) : 'N/A' }} 
                &bull; <span class="capitalize">{{ $content->type->value ?? 'Unknown' }}</span>
            </span>
            @if($content->age_rating)
                <span class="inline-block px-1.5 border border-gray-300 dark:border-zinc-700 rounded text-[10px] font-semibold tracking-wider flex-shrink-0">
                    {{ $content->age_rating }}
                </span>
            @endif
        </div>
    </div>
</a>
EOT,

    'public/home.blade.php' => <<<'EOT'
@extends('layouts.public')

@section('title', 'NontonKu - Home')
@section('meta_description', 'Discover the best movies, series, anime, and donghua on NontonKu.')

@section('content')
<div class="py-12">
    <x-container>
        @if($trending->isNotEmpty())
            <x-section-heading title="Trending Now" />
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                @foreach($trending as $content)
                    <x-content-card :content="$content" />
                @endforeach
            </div>
        @endif

        @if($series->isNotEmpty())
            <div class="mt-16">
                <x-section-heading title="Latest Series" actionText="Explore series" actionUrl="{{ route('series') }}" />
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                    @foreach($series as $content)
                        <x-content-card :content="$content" />
                    @endforeach
                </div>
            </div>
        @endif
    </x-container>
</div>
@endsection
EOT,

    'public/catalog.blade.php' => <<<'EOT'
@extends('layouts.public')

@section('title', $title . ' - NontonKu')
@section('meta_description', 'Browse all ' . strtolower($title) . ' available on NontonKu.')

@section('content')
<div class="py-12">
    <x-container>
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $title }}</h1>
            
            <form method="GET" action="{{ url()->current() }}" class="flex items-center space-x-4">
                @if(request('genre'))
                    <input type="hidden" name="genre" value="{{ request('genre') }}">
                @endif
                <select name="sort" onchange="this.form.submit()" class="block w-40 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-zinc-700 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popular</option>
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Top Rated</option>
                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Alphabetical</option>
                </select>
            </form>
        </div>

        @if($contents->isEmpty())
            <x-empty-state title="No {{ strtolower($title) }} found" description="We couldn't find any content matching your criteria." />
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
EOT,

    'public/search.blade.php' => <<<'EOT'
@extends('layouts.public')

@section('title', 'Search Results - NontonKu')

@section('content')
<div class="py-12">
    <x-container>
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                Search Results @if($q) for "{{ $q }}" @endif
            </h1>
        </div>

        <form action="{{ route('search') }}" method="GET" class="max-w-2xl mb-12">
            <div class="flex rounded-md shadow-sm">
                <input type="text" name="q" value="{{ $q }}" class="block w-full rounded-none rounded-l-md border-0 py-2.5 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-zinc-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800" placeholder="Search for movies, series, anime...">
                <button type="submit" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-4 py-2 text-sm font-semibold text-white bg-indigo-600 ring-1 ring-inset ring-indigo-600 hover:bg-indigo-500 focus:z-10">
                    Search
                </button>
            </div>
        </form>

        @if($contents->isEmpty())
            <x-empty-state title="No results found" description="Try adjusting your search terms." />
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
EOT,

    'public/show.blade.php' => <<<'EOT'
@extends('layouts.public')

@section('title', $content->title . ' - NontonKu')
@section('meta_description', Str::limit(strip_tags($content->description), 150))
@section('canonical', url()->current())

@push('meta')
<meta property="og:title" content="{{ $content->title }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($content->description), 150) }}">
<meta property="og:url" content="{{ url()->current() }}">
@if($content->poster_path)
<meta property="og:image" content="{{ url($content->poster_path) }}">
@endif

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "{{ $content->type->value === 'movie' ? 'Movie' : 'TVSeries' }}",
    "name": {!! json_encode($content->title) !!},
    "description": {!! json_encode($content->description) !!},
    @if($content->poster_path)
    "image": {!! json_encode(url($content->poster_path)) !!},
    @endif
    @if($content->release_date)
    "datePublished": "{{ $content->release_date }}",
    @endif
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ $content->average_rating }}",
        "ratingCount": "{{ max(1, $content->ratings_count) }}"
    }
}
</script>
@endpush

@section('content')
<div class="relative w-full h-96 lg:h-[32rem] bg-gray-900 overflow-hidden">
    @if($content->backdrop_path)
        <img src="{{ $content->backdrop_path }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-30">
    @else
        <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-zinc-900 to-indigo-900 opacity-80"></div>
    @endif
    
    <div class="absolute inset-0 bg-gradient-to-t from-gray-50 dark:from-zinc-950 to-transparent"></div>
    
    <x-container class="relative h-full flex flex-col justify-end pb-12">
        <div class="flex flex-col md:flex-row md:items-end gap-8">
            <div class="w-32 md:w-48 flex-shrink-0 rounded-xl overflow-hidden shadow-2xl border-4 border-white dark:border-zinc-800">
                @if($content->poster_path)
                    <img src="{{ $content->poster_path }}" alt="{{ $content->title }}" class="w-full h-auto">
                @else
                    <div class="w-full aspect-[2/3] bg-zinc-800"></div>
                @endif
            </div>
            
            <div class="flex-grow">
                <div class="flex flex-wrap items-center gap-3 mb-3">
                    <x-badge type="primary" class="capitalize">{{ $content->type->value }}</x-badge>
                    @if($content->age_rating)
                        <span class="inline-block px-1.5 border border-gray-300 dark:border-zinc-600 rounded text-xs font-semibold text-gray-700 dark:text-zinc-300 tracking-wider">
                            {{ $content->age_rating }}
                        </span>
                    @endif
                    <div class="flex items-center text-yellow-400 font-bold text-sm">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        {{ number_format($content->average_rating, 1) }}
                    </div>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $content->title }}</h1>
                @if($content->original_title && $content->original_title !== $content->title)
                    <p class="mt-1 text-lg text-gray-600 dark:text-zinc-400 italic">{{ $content->original_title }}</p>
                @endif
                
                <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-gray-700 dark:text-zinc-300">
                    <span>{{ $content->release_date ? substr($content->release_date, 0, 4) : 'Unknown Year' }}</span>
                    @if($content->duration_minutes)
                        <span>&bull;</span>
                        <span>{{ intdiv($content->duration_minutes, 60) }}h {{ $content->duration_minutes % 60 }}m</span>
                    @endif
                    <span>&bull;</span>
                    <span class="capitalize">{{ $content->status->value }}</span>
                </div>
            </div>
        </div>
    </x-container>
</div>

<x-container class="py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Synopsis</h2>
            <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-zinc-400">
                <p>{{ $content->description ?: 'No synopsis available.' }}</p>
            </div>
            
            @if($content->type->value !== 'movie' && $content->seasons->isNotEmpty())
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Episodes</h2>
                    <div class="space-y-8">
                        @foreach($content->seasons as $season)
                            @if($season->episodes->isNotEmpty())
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-zinc-200 mb-4">{{ $season->title ?: 'Season ' . $season->season_number }}</h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach($season->episodes as $episode)
                                            <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                                                <div class="aspect-video bg-gray-200 dark:bg-zinc-800 relative">
                                                    @if($episode->thumbnail_path)
                                                        <img src="{{ $episode->thumbnail_path }}" class="w-full h-full object-cover">
                                                    @endif
                                                    <div class="absolute bottom-2 right-2 bg-black/80 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">
                                                        EP {{ $episode->episode_number }}
                                                    </div>
                                                </div>
                                                <div class="p-3">
                                                    <h4 class="font-semibold text-sm text-gray-900 dark:text-white truncate" title="{{ $episode->title ?: 'Episode ' . $episode->episode_number }}">
                                                        {{ $episode->title ?: 'Episode ' . $episode->episode_number }}
                                                    </h4>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        
        <div class="space-y-8">
            @if($content->genres->isNotEmpty())
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-zinc-500 uppercase tracking-wider mb-3">Genres</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($content->genres as $genre)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-zinc-800 dark:text-zinc-300">
                                {{ $genre->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <div class="bg-gray-50 dark:bg-zinc-900/50 rounded-xl p-6 border border-gray-100 dark:border-zinc-800/50">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Availability</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-zinc-400">Stream</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $content->videoSources->isNotEmpty() ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-zinc-400">Download</span>
                        <span class="font-medium text-gray-900 dark:text-white">Unavailable</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-zinc-400">Views</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($content->views_count) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-container>
@endsection
EOT,
];

foreach ($views as $path => $content) {
    @mkdir(dirname(__DIR__ . '/resources/views/' . $path), 0777, true);
    file_put_contents(__DIR__ . '/resources/views/' . $path, $content);
}
echo "View files created successfully.\n";
