@extends('layouts.public')

@section('title', $content->title . ' - ' . ucfirst($content->type->value) . ' - NontonKu')
@section('meta_description', Str::limit(strip_tags($content->description), 150))
@section('canonical', url()->current())

@push('meta')
<meta property="og:title" content="{{ $content->title }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($content->description), 150) }}">
<meta property="og:url" content="{{ url()->current() }}">
@if($content->poster_url)
<meta property="og:image" content="{{ url($content->poster_url) }}">
@endif

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "{{ $content->type->value === 'movie' ? 'Movie' : 'TVSeries' }}",
    "name": {!! json_encode($content->title) !!},
    "description": {!! json_encode($content->description) !!},
    @if($content->poster_url)
    "image": {!! json_encode(url($content->poster_url)) !!},
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
<div class="relative w-full h-[60vh] min-h-[500px] bg-black overflow-hidden">
    @if($content->backdrop_url)
        <img src="{{ $content->backdrop_url }}" alt="" class="absolute inset-0 w-full h-full object-cover object-top opacity-50">
    @else
        <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-zinc-900 via-zinc-800 to-indigo-950 opacity-80"></div>
    @endif
    
    <div class="absolute inset-0 bg-gradient-to-t from-gray-50 via-transparent dark:from-zinc-950 dark:via-zinc-950/60 to-transparent"></div>
    
    <x-container class="relative h-full flex flex-col justify-end pb-12 z-10">
        <div class="flex flex-col md:flex-row md:items-end gap-8">
            <div class="w-40 md:w-56 flex-shrink-0 rounded-2xl overflow-hidden shadow-2xl shadow-black/50 border-4 border-white/50 dark:border-white/10 backdrop-blur-sm">
                @if($content->poster_url)
                    <img src="{{ $content->poster_url }}" alt="{{ $content->title }}" class="w-full h-auto">
                @else
                    <div class="w-full aspect-[2/3] bg-zinc-800/80"></div>
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
                
                <div class="flex items-center gap-4">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $content->title }}</h1>
                    
                    <div x-data="{ 
                            isFavorited: {{ $userFavorite ? 'true' : 'false' }}, 
                            isLoading: false,
                            toggle() {
                                @guest
                                    window.location.href = '{{ route('login') }}';
                                    return;
                                @endguest
                                
                                if (this.isLoading) return;
                                this.isLoading = true;
                                const originalState = this.isFavorited;
                                this.isFavorited = !this.isFavorited; // optimistic UI update
                                
                                const method = originalState ? 'DELETE' : 'POST';
                                
                                fetch('{{ route('favorites.store', $content->id) }}', {
                                    method: method,
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                        'Accept': 'application/json'
                                    }
                                }).then(response => {
                                    if (!response.ok) throw new Error('Network response was not ok');
                                }).catch(error => {
                                    this.isFavorited = originalState; // rollback on fail
                                    console.error('Error:', error);
                                }).finally(() => {
                                    this.isLoading = false;
                                });
                            }
                        }">
                        <button @@click="toggle" 
                                :disabled="isLoading"
                                :class="{'text-red-500 hover:text-red-600': isFavorited, 'text-gray-400 hover:text-gray-500': !isFavorited}"
                                class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                aria-label="Toggle Favorite">
                            <svg class="w-8 h-8 transition-transform" :class="{'scale-110': isFavorited}" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                @if($content->type->value === 'movie' && $content->videoSources->isNotEmpty())
                    <div class="mt-4 mb-2 flex flex-wrap gap-3">
                        <a href="{{ route('watch.movie', $content->slug) }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-indigo-500/30 transition-all duration-300 hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
                            Play Movie
                        </a>
                        
                        @php
                            $downloadableSource = $content->videoSources->where('is_active', true)->where('provider', 'mp4')->where('is_downloadable', true)->sortByDesc('priority')->first();
                        @endphp
                        
                        @if($downloadableSource)
                            <a href="{{ route('watch.download.movie', ['content' => $content->slug, 'source' => $downloadableSource->id]) }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700 text-gray-900 dark:text-white font-semibold rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download
                            </a>
                        @endif
                    </div>
                @endif
                
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
                                            <div class="group bg-white/60 dark:bg-zinc-900/40 backdrop-blur-md border border-gray-200/50 dark:border-white/5 rounded-xl overflow-hidden shadow-sm hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 hover:-translate-y-1">
                                                <a href="{{ route('watch.' . $content->type->value, ['content' => $content->slug, 'episode' => $episode->id]) }}" class="block">
                                                    <div class="aspect-video bg-gray-200 dark:bg-zinc-800 relative overflow-hidden">
                                                        @if($episode->thumbnail_url)
                                                            <img src="{{ $episode->thumbnail_url }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                                        @endif
                                                        <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors duration-300"></div>
                                                        <div class="absolute bottom-2 right-2 bg-black/80 backdrop-blur-md text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                                                            EP {{ $episode->episode_number }}
                                                        </div>
                                                        
                                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                            <div class="bg-indigo-600/90 text-white rounded-full p-3 shadow-lg transform scale-90 group-hover:scale-100 transition-transform duration-300">
                                                                <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="p-4 relative">
                                                    <h4 class="font-semibold text-sm text-gray-900 dark:text-white truncate" title="{{ $episode->title ?: 'Episode ' . $episode->episode_number }}">
                                                        {{ $episode->title ?: 'Episode ' . $episode->episode_number }}
                                                    </h4>
                                                    
                                                    <div class="absolute top-3 right-3"
                                                        x-data="{ 
                                                            isBookmarked: {{ in_array($episode->id, $bookmarkedEpisodes) ? 'true' : 'false' }}, 
                                                            isLoading: false,
                                                            toggle() {
                                                                @guest
                                                                    window.location.href = '{{ route('login') }}';
                                                                    return;
                                                                @endguest
                                                                
                                                                if (this.isLoading) return;
                                                                this.isLoading = true;
                                                                const originalState = this.isBookmarked;
                                                                this.isBookmarked = !this.isBookmarked;
                                                                
                                                                const method = originalState ? 'DELETE' : 'POST';
                                                                
                                                                fetch('/watchlist/{{ $episode->id }}', {
                                                                    method: method,
                                                                    headers: {
                                                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                                                        'Accept': 'application/json'
                                                                    }
                                                                }).catch(error => {
                                                                    this.isBookmarked = originalState;
                                                                }).finally(() => {
                                                                    this.isLoading = false;
                                                                });
                                                            }
                                                        }">
                                                        <button @@click.prevent="toggle" 
                                                                :disabled="isLoading"
                                                                :class="{'text-indigo-500 bg-indigo-50 dark:bg-indigo-500/10': isBookmarked, 'text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 dark:bg-zinc-800 dark:hover:bg-zinc-700': !isBookmarked}"
                                                                class="p-1.5 rounded-full transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                                aria-label="Toggle Bookmark">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                                                            </svg>
                                                        </button>
                                                    </div>
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
            
            <div class="bg-white/60 dark:bg-zinc-900/40 backdrop-blur-md rounded-2xl p-6 border border-gray-200/50 dark:border-white/5 shadow-sm">
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
                        <span class="font-medium text-gray-900 dark:text-white">
                            @if($content->type->value === 'movie')
                                {{ $content->videoSources->where('is_active', true)->where('provider', 'mp4')->where('is_downloadable', true)->isNotEmpty() ? 'Available' : 'Unavailable' }}
                            @else
                                Per Episode
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-zinc-400">Views</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($content->views_count) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white/60 dark:bg-zinc-900/40 backdrop-blur-md rounded-2xl p-6 border border-gray-200/50 dark:border-white/5 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Rate this</h3>
                
                @auth
                    <form action="{{ route('ratings.store', $content->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="flex items-center gap-2">
                            <select name="rating" class="block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" aria-label="Rating out of 10">
                                <option value="">Select rating...</option>
                                @for($i = 10; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ $userRating == $i ? 'selected' : '' }}>{{ $i }} / 10 {{ $userRating == $i ? '(Your Rating)' : '' }}</option>
                                @endfor
                            </select>
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900">
                                Save
                            </button>
                        </div>
                        @error('rating')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                        @if(session('status'))
                            <p class="text-green-500 dark:text-green-400 text-xs">{{ session('status') }}</p>
                        @endif
                    </form>
                @else
                    <div class="text-sm text-gray-600 dark:text-zinc-400">
                        <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Log in</a> to rate this {{ $content->type->value }}.
                    </div>
                @endauth
            </div>
        </div>
    </div>

    @if(isset($relatedContent) && $relatedContent->isNotEmpty())
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">You May Also Like</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                @foreach($relatedContent as $related)
                    <x-content-card :content="$related" />
                @endforeach
            </div>
        </div>
    @endif
</x-container>
@endsection
