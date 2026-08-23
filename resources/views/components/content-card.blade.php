@props(['content'])

@php
    // To support generating proper routes based on type
    $routeName = $content->type->value . 's.show';
    if ($content->type->value === 'anime') $routeName = 'anime.show';
    if ($content->type->value === 'donghua') $routeName = 'donghua.show';
    if ($content->type->value === 'series') $routeName = 'series.show';

    $isFavorited = false;
    if (auth()->check()) {
        $isFavorited = auth()->user()->favorites->contains('content_id', $content->id);
    }
@endphp

<a href="{{ route($routeName, $content->slug) }}" class="group block relative rounded-xl overflow-hidden bg-white/50 dark:bg-zinc-900/50 backdrop-blur-sm border border-gray-200/50 dark:border-white/5 shadow-sm hover:shadow-2xl hover:shadow-indigo-500/10 dark:hover:shadow-indigo-500/20 transition-all duration-300 hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-950">
    <div class="aspect-[2/3] bg-gray-200 dark:bg-zinc-800 relative overflow-hidden">
        @if($content->poster_url)
            <img src="{{ $content->poster_url }}" alt="{{ $content->title }}" class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110" loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        @else
            <div class="flex items-center justify-center w-full h-full text-gray-400 dark:text-zinc-600">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif

        @if($content->average_rating > 0)
            <div class="absolute top-2 right-2 bg-black/70 backdrop-blur-md text-white text-xs font-bold px-2 py-1 rounded-md flex items-center shadow-sm transition-transform duration-300 group-hover:scale-110">
                <svg class="w-3 h-3 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                {{ number_format($content->average_rating, 1) }}
            </div>
        @endif
        
        <div class="absolute top-2 left-2"
            x-data="{ 
                isFavorited: {{ $isFavorited ? 'true' : 'false' }}, 
                isLoading: false,
                toggle(e) {
                    @guest
                        window.location.href = '{{ route('login') }}';
                        return;
                    @endguest
                    
                    if (this.isLoading) return;
                    this.isLoading = true;
                    const originalState = this.isFavorited;
                    this.isFavorited = !this.isFavorited;
                    
                    const method = originalState ? 'DELETE' : 'POST';
                    
                    fetch('/favorites/{{ $content->id }}', {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json'
                        }
                    }).catch(error => {
                        this.isFavorited = originalState;
                    }).finally(() => {
                        this.isLoading = false;
                    });
                }
            }">
            <button @@click.prevent="toggle" 
                    :disabled="isLoading"
                    :class="{'text-red-500 bg-white/90': isFavorited, 'text-white/70 bg-black/40 hover:bg-black/70 hover:text-white': !isFavorited}"
                    aria-label="Toggle favorite" 
                    class="p-1.5 rounded-full backdrop-blur-md transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">
                <svg class="w-4 h-4" :fill="isFavorited ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>
        </div>
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
