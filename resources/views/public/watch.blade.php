@extends('layouts.public')

@section('title', 'Watch ' . ($episode ? $episode->title ?? 'Episode ' . $episode->episode_number : $content->title) . ' - NontonKu')
@section('meta_robots', 'noindex, nofollow')

@section('content')
<div class="bg-black text-white min-h-[calc(100vh-4rem)] flex flex-col">
    <div class="flex-grow flex flex-col lg:flex-row max-w-[1600px] w-full mx-auto"
        x-data="playerData()" 
        x-init="initPlayer()"
    >
        <!-- Main Player Area -->
        <div class="flex-grow relative flex flex-col w-full" :class="{'lg:w-[70%] xl:w-[75%]': hasEpisodes}">
            <!-- Player Container -->
            <div class="relative w-full bg-black flex-grow flex items-center justify-center overflow-hidden aspect-video lg:aspect-auto" 
                 @@mousemove="showControls" 
                 @@mouseleave="hideControls"
                 @@keydown.window="handleKeydown"
                 @@fullscreenchange="isFullscreen = !!document.fullscreenElement"
                 x-ref="playerContainer"
            >
                @if($videoSources->isEmpty())
                    <!-- Empty Source State -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-zinc-900 z-10">
                        <svg class="w-16 h-16 text-zinc-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <h3 class="text-xl font-medium text-white mb-2">Video Source Unavailable</h3>
                        <p class="text-zinc-400 mb-6 text-center max-w-md">We're sorry, but the video source for this content is currently unavailable or broken.</p>
                        <a href="{{ route($content->type->value === 'movie' ? 'movies.show' : $content->type->value . '.show', $content->slug) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded font-medium transition-colors">
                            Return to Details
                        </a>
                    </div>
                @else
                    @if($activeSource->provider === 'embed')
                        <!-- Embed Iframe -->
                        {{-- <div class="w-full h-full relative bg-black">
                            <iframe 
                                src="{{ $activeSource->url }}" 
                                class="w-full h-full absolute inset-0 border-0" 
                                allowfullscreen 
                                allow="{{ $activeSource->supports_autoplay ? 'autoplay; ' : '' }}fullscreen"
                                sandbox="allow-same-origin allow-scripts allow-presentation"
                                @@load="isLoading = false"
                                @@error="hasError = true"
                            ></iframe>
                        </div> --}}
                        <!-- Embed Iframe -->
                        <div class="w-full h-full relative bg-black">
                            <iframe 
                                src="{{ $activeSource->url }}" 
                                class="w-full h-full absolute inset-0 border-0" 
                                allow="{{ $activeSource->supports_autoplay ? 'autoplay; ' : '' }}encrypted-media; picture-in-picture; fullscreen"
                                referrerpolicy="no-referrer-when-downgrade"
                                @@load="isLoading = false"
                                @@error="hasError = true"
                            ></iframe>
                        </div>
                    @else
                        <!-- Native Video Element (MP4 / HLS) -->
                        <video 
                            id="video-player"
                            x-ref="video"
                            class="w-full h-full object-contain"
                            @@play="isPlaying = true; isLoading = false"
                            @@pause="isPlaying = false; saveProgress()"
                            @@timeupdate="onTimeUpdate"
                            @@loadedmetadata="onLoadedMetadata"
                            @@waiting="isLoading = true"
                            @@playing="isLoading = false"
                            @@ended="onEnded"
                            @@error="hasError = true"
                            @if($activeSource->provider === 'mp4') src="{{ $activeSource->url }}" @endif
                        ></video>
                    @endif

                    <!-- Loading State -->
                    <div x-show="isLoading && !hasError && !showResumePrompt" class="absolute inset-0 flex items-center justify-center bg-black/40 pointer-events-none z-10">
                        <svg class="animate-spin w-12 h-12 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <!-- Error State -->
                    <div x-show="hasError" class="absolute inset-0 flex flex-col items-center justify-center bg-zinc-900 z-20" style="display: none;">
                        <svg class="w-16 h-16 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="text-xl font-medium text-white mb-2">Playback Error</h3>
                        <p class="text-zinc-400 mb-6">There was a problem playing this video from the selected server.</p>
                        <button @@click="retry" class="px-4 py-2 bg-white text-black hover:bg-gray-200 rounded font-medium transition-colors">
                            Retry
                        </button>
                    </div>

                    <!-- Resume Prompt Overlay -->
                    <div x-show="showResumePrompt" class="absolute inset-0 flex items-center justify-center bg-black/80 z-30 backdrop-blur-sm" style="display: none;">
                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-white mb-2">Resume Playback?</h3>
                            <p class="text-zinc-300 mb-8">You left off at <span x-text="formatTime(resumeTime)" class="font-mono text-white"></span></p>
                            <div class="flex items-center justify-center gap-4">
                                <button @@click="startFromBeginning" class="px-6 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-white rounded font-medium transition-colors">
                                    Start Over
                                </button>
                                <button @@click="resumePlayback" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded font-medium transition-colors shadow-lg shadow-indigo-600/30 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
                                    Resume
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Auto Next Episode Overlay -->
                    <div x-show="showNextEpisodePrompt" class="absolute inset-0 flex items-end justify-end p-8 bg-black/60 z-30 opacity-0 transition-opacity duration-500" :class="{'opacity-100': showNextEpisodePrompt}" style="display: none;">
                        <div class="bg-zinc-900/90 border border-zinc-700 p-6 rounded-lg shadow-2xl max-w-sm w-full backdrop-blur-md">
                            <h4 class="text-zinc-400 text-sm font-medium mb-1">Up Next in <span x-text="nextEpisodeCountdown"></span>s</h4>
                            <h3 class="text-lg font-bold text-white mb-4 truncate">{{ $nextEpisode ? ($nextEpisode->title ?? 'Episode ' . $nextEpisode->episode_number) : '' }}</h3>
                            <div class="flex gap-3">
                                <button @@click="cancelNextEpisode" class="flex-1 py-2 bg-zinc-800 hover:bg-zinc-700 text-white rounded font-medium transition-colors text-sm">
                                    Cancel
                                </button>
                                <button @@click="goToNextEpisode" class="flex-1 py-2 bg-white text-black hover:bg-gray-200 rounded font-medium transition-colors text-sm">
                                    Play Next
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Video Controls Overlay -->
                    <div class="absolute inset-0 z-20 pointer-events-none" @@click.self="togglePlay">
                        <!-- Top Bar (Title & Back) -->
                        <div class="absolute top-0 left-0 right-0 p-4 bg-gradient-to-b from-black/80 to-transparent transition-opacity duration-300 pointer-events-auto flex items-center justify-between"
                             :class="controlsVisible ? 'opacity-100' : 'opacity-0'"
                        >
                            <a href="{{ route($content->type->value === 'movie' ? 'movies.show' : $content->type->value . '.show', $content->slug) }}" class="text-white hover:text-indigo-400 transition-colors flex items-center group">
                                <svg class="w-6 h-6 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                <div>
                                    <h1 class="text-lg font-bold leading-none">{{ $content->title }}</h1>
                                    @if($episode)
                                        <p class="text-xs text-zinc-300 mt-1">Season {{ $episode->season->season_number }} &bull; Episode {{ $episode->episode_number }}{{ $episode->title ? ' - ' . $episode->title : '' }}</p>
                                    @endif
                                </div>
                            </a>

                            @if($activeSource->provider === 'mp4' && $activeSource->is_downloadable)
                                @php
                                    $downloadRoute = $content->type->value === 'movie' 
                                        ? route('watch.download.movie', ['content' => $content->slug, 'source' => $activeSource->id])
                                        : route('watch.download.'.$content->type->value, ['content' => $content->slug, 'episode' => $episode->id, 'source' => $activeSource->id]);
                                @endphp
                                <a href="{{ $downloadRoute }}" target="_blank" title="Download" class="flex items-center gap-2 px-3 py-1.5 bg-black/50 hover:bg-black/80 rounded-md backdrop-blur-md border border-zinc-700 transition-colors text-sm font-medium text-white hover:text-indigo-400 mr-2 pointer-events-auto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    <span class="hidden sm:inline">Download</span>
                                </a>
                            @endif

                            @if($videoSources->count() > 1)
                            <div class="relative pointer-events-auto" x-data="{ openSources: false }" @@click.away="openSources = false">
                                <button @@click="openSources = !openSources" class="flex items-center gap-2 px-3 py-1.5 bg-black/50 hover:bg-black/80 rounded-md backdrop-blur-md border border-zinc-700 transition-colors text-sm font-medium">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                                    Server: {{ $activeSource->server_name }}
                                </button>
                                <div x-show="openSources" class="absolute right-0 mt-2 w-48 bg-zinc-900 border border-zinc-700 rounded-lg shadow-xl py-2 z-50" style="display: none;">
                                    <div class="px-3 py-1 text-xs text-zinc-500 font-semibold uppercase tracking-wider mb-1">Select Server</div>
                                    @foreach($videoSources as $source)
                                        <a href="{{ request()->fullUrlWithQuery(['source_id' => $source->id]) }}" 
                                           class="block px-4 py-2 text-sm hover:bg-zinc-800 transition-colors {{ $activeSource->id === $source->id ? 'text-indigo-400 font-bold border-l-2 border-indigo-400 bg-zinc-800/50' : 'text-white border-l-2 border-transparent' }}">
                                            {{ $source->server_name }}
                                            <span class="block text-xs text-zinc-500 font-normal mt-0.5">{{ strtoupper($source->provider) }} {{ $source->quality ? '• ' . $source->quality : '' }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Big Play/Pause Center Indicator (Animation only) -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none" @if($activeSource->provider === 'embed') style="display:none;" @endif>
                            <div x-show="showPlayAnimation" class="bg-black/50 rounded-full p-4 animate-ping-short" style="display: none;">
                                <svg x-show="isPlaying" class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                <svg x-show="!isPlaying" class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
                            </div>
                        </div>

                        <!-- Bottom Controls -->
                        <div class="absolute bottom-0 left-0 right-0 px-4 pb-4 pt-16 bg-gradient-to-t from-black/90 via-black/50 to-transparent transition-opacity duration-300 pointer-events-auto"
                             :class="controlsVisible ? 'opacity-100' : 'opacity-0'"
                             @if($activeSource->provider === 'embed') style="display:none;" @endif
                        >
                            <!-- Progress Bar -->
                            <div class="flex items-center w-full group mb-4 cursor-pointer relative h-2" @@click="seek" @@mousemove="updatePreviewTime" @@mouseenter="showPreview = true" @@mouseleave="showPreview = false">
                                <div class="absolute top-0 bottom-0 left-0 bg-zinc-600 rounded-full w-full"></div>
                                <div class="absolute top-0 bottom-0 left-0 bg-zinc-400 rounded-full transition-all duration-150" :style="`width: ${bufferedPercentage}%`"></div>
                                <div class="absolute top-0 bottom-0 left-0 bg-indigo-500 rounded-full transition-all duration-75 relative" :style="`width: ${progressPercentage}%`">
                                    <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 w-3.5 h-3.5 bg-white rounded-full scale-0 group-hover:scale-100 transition-transform"></div>
                                </div>
                                <!-- Time Preview tooltip -->
                                <div x-show="showPreview" class="absolute -top-8 -translate-x-1/2 bg-black text-white text-xs px-2 py-1 rounded" :style="`left: ${previewPercentage}%`" x-text="formatTime(previewTime)" style="display: none;"></div>
                            </div>

                            <!-- Buttons Row -->
                            <div class="flex items-center justify-between text-white">
                                <div class="flex items-center gap-4">
                                    <!-- Play/Pause -->
                                    <button @@click="togglePlay" class="focus:outline-none hover:text-indigo-400 transition-colors">
                                        <svg x-show="!isPlaying" class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
                                        <svg x-show="isPlaying" style="display:none;" class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    </button>

                                    <!-- Volume -->
                                    <div class="flex items-center gap-2 group relative">
                                        <button @@click="toggleMute" class="focus:outline-none hover:text-indigo-400 transition-colors">
                                            <svg x-show="isMuted || volume === 0" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"></path></svg>
                                            <svg x-show="!isMuted && volume > 0 && volume <= 0.5" style="display:none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072"></path></svg>
                                            <svg x-show="!isMuted && volume > 0.5" style="display:none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                                        </button>
                                        <input type="range" min="0" max="1" step="0.05" x-model="volume" @@input="updateVolume" class="w-0 overflow-hidden group-hover:w-20 transition-all duration-300 h-1.5 bg-zinc-600 rounded-lg appearance-none cursor-pointer">
                                    </div>

                                    <!-- Time Display -->
                                    <div class="text-sm font-mono text-zinc-300 select-none">
                                        <span x-text="formatTime(currentTime)"></span> / <span x-text="formatTime(duration)"></span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4">
                                    @if($episode && $episodes->isNotEmpty())
                                        <!-- Prev/Next Episode Buttons -->
                                        @if($prevEpisode)
                                            <a href="{{ route('watch.'.$content->type->value, ['content' => $content->slug, 'episode' => $prevEpisode->id]) }}" class="hidden sm:block text-zinc-300 hover:text-white transition-colors" title="Previous Episode">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                                            </a>
                                        @endif
                                        @if($nextEpisode)
                                            <a href="{{ route('watch.'.$content->type->value, ['content' => $content->slug, 'episode' => $nextEpisode->id]) }}" class="hidden sm:block text-zinc-300 hover:text-white transition-colors" title="Next Episode">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                                            </a>
                                        @endif
                                    @endif

                                    <!-- Settings (Speed) -->
                                    <div class="relative" x-data="{ open: false }" @@click.away="open = false">
                                        <button @@click="open = !open" class="focus:outline-none hover:text-indigo-400 transition-colors" title="Settings">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </button>
                                        <div x-show="open" class="absolute bottom-full right-0 mb-2 w-32 bg-zinc-900 border border-zinc-700 rounded shadow-lg py-1 z-50 text-sm" style="display: none;">
                                            <div class="px-3 py-1 text-xs text-zinc-500 font-semibold uppercase tracking-wider">Speed</div>
                                            <template x-for="s in [0.5, 0.75, 1, 1.25, 1.5, 2]" :key="s">
                                                <button @@click="setSpeed(s); open = false" class="w-full text-left px-4 py-1.5 hover:bg-zinc-800 transition-colors flex items-center justify-between">
                                                    <span x-text="s + 'x'"></span>
                                                    <svg x-show="playbackRate === s" class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                                </button>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Fullscreen -->
                                    <button @@click="toggleFullscreen" class="focus:outline-none hover:text-indigo-400 transition-colors">
                                        <svg x-show="!isFullscreen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                                        <svg x-show="isFullscreen" style="display:none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14h4m0 0v4m0-4l-5 5m11-5h-4m0 0v4m0-4l5 5M4 10V6m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Mobile Below-Player Details -->
            <div class="lg:hidden p-4 bg-zinc-900 border-b border-zinc-800">
                <h1 class="text-xl font-bold text-white">{{ $content->title }}</h1>
                @if($episode)
                    <p class="text-zinc-400 mt-1">Season {{ $episode->season->season_number }} &bull; Episode {{ $episode->episode_number }}</p>
                @endif
            </div>
        </div>

        <!-- Sidebar / Episodes List -->
        @if($episodes->isNotEmpty())
            <div class="w-full lg:w-[30%] xl:w-[25%] bg-zinc-900 lg:border-l border-zinc-800 flex flex-col h-auto lg:h-[calc(100vh-4rem)] overflow-hidden">
                <div class="p-4 border-b border-zinc-800 flex-shrink-0">
                    <h3 class="text-lg font-bold text-white">Episodes</h3>
                </div>
                <div class="flex-grow overflow-y-auto overflow-x-hidden custom-scrollbar">
                    @php $currentSeason = null; @endphp
                    @foreach($episodes as $ep)
                        @if($currentSeason !== $ep->season->season_number)
                            @php $currentSeason = $ep->season->season_number; @endphp
                            <div class="px-4 py-2 bg-zinc-950/50 text-xs font-bold text-zinc-500 uppercase tracking-wider sticky top-0 z-10 backdrop-blur-md">
                                Season {{ $currentSeason }}
                            </div>
                        @endif
                        
                        @php $isActive = $episode && $episode->id === $ep->id; @endphp
                        <a href="{{ route('watch.'.$content->type->value, ['content' => $content->slug, 'episode' => $ep->id]) }}" 
                           class="flex items-center gap-3 p-3 transition-colors border-b border-zinc-800/50 hover:bg-zinc-800 {{ $isActive ? 'bg-indigo-900/20 border-l-4 border-l-indigo-500' : 'border-l-4 border-l-transparent' }}">
                            <div class="flex-shrink-0 w-24 aspect-video bg-zinc-800 rounded overflow-hidden relative">
                                @if($ep->thumbnail_url)
                                    <img src="{{ $ep->thumbnail_url }}" class="w-full h-full object-cover">
                                @endif
                                @if($isActive)
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-white truncate {{ $isActive ? 'text-indigo-400' : '' }}">
                                    {{ $ep->episode_number }}. {{ $ep->title ?: 'Episode ' . $ep->episode_number }}
                                </h4>
                                <p class="text-xs text-zinc-500 mt-1">{{ $ep->duration_minutes ? $ep->duration_minutes . 'm' : '' }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #52525b; }
    .animate-ping-short { animation: ping-short 0.5s cubic-bezier(0, 0, 0.2, 1) forwards; }
    @keyframes ping-short { 
        0% { transform: scale(1); opacity: 1; }
        100% { transform: scale(1.5); opacity: 0; }
    }
</style>

@if($videoSources->isNotEmpty())
@if($activeSource->provider === 'hls')
    <script src="https://cdn.jsdelivr.net/npm/hls.js@1"></script>
@endif
<script>
    function playerData() {
        return {
            video: null,
            isPlaying: false,
            isLoading: true,
            hasError: false,
            isMuted: false,
            volume: 1,
            currentTime: 0,
            duration: 0,
            bufferedPercentage: 0,
            progressPercentage: 0,
            playbackRate: 1,
            isFullscreen: false,
            
            // Controls visibility
            controlsVisible: true,
            controlsTimeout: null,
            showPlayAnimation: false,
            
            // Preview
            showPreview: false,
            previewTime: 0,
            previewPercentage: 0,
            
            // Resume Feature
            resumeTime: {{ $watchHistory && !$watchHistory->is_completed && $watchHistory->progress_seconds > 0 ? $watchHistory->progress_seconds : 0 }},
            showResumePrompt: false,
            
            // Auto Next Episode
            hasEpisodes: {{ $episodes->isNotEmpty() ? 'true' : 'false' }},
            nextEpisodeUrl: '{{ $nextEpisode ? route('watch.'.$content->type->value, ['content' => $content->slug, 'episode' => $nextEpisode->id]) : '' }}',
            showNextEpisodePrompt: false,
            nextEpisodeCountdown: 5,
            nextEpisodeTimer: null,
            
            // History Sync
            isAuth: {{ Auth::check() ? 'true' : 'false' }},
            contentId: {{ $content->id }},
            episodeId: {{ $episode ? $episode->id : 'null' }},
            lastSyncTime: 0,

            initPlayer() {
                this.video = this.$refs.video;
                if (!this.video && '{{ $activeSource->provider }}' !== 'embed') return;
                
                if (this.video) {
                    this.volume = this.video.volume;
                    this.isMuted = this.video.muted;
                    
                    @if($activeSource->provider === 'hls')
                    if (Hls.isSupported()) {
                        var hls = new Hls({
                            debug: false,
                        });
                        hls.loadSource('{{ $activeSource->url }}');
                        hls.attachMedia(this.video);
                        hls.on(Hls.Events.ERROR, (event, data) => {
                            if (data.fatal) {
                                switch (data.type) {
                                    case Hls.ErrorTypes.NETWORK_ERROR:
                                        console.log('fatal network error encountered, try to recover');
                                        hls.startLoad();
                                        break;
                                    case Hls.ErrorTypes.MEDIA_ERROR:
                                        console.log('fatal media error encountered, try to recover');
                                        hls.recoverMediaError();
                                        break;
                                    default:
                                        // cannot recover
                                        hls.destroy();
                                        this.hasError = true;
                                        break;
                                }
                            }
                        });
                    }
                    @endif
                }
                
                // If there's a resume time, pause and show prompt
                if (this.resumeTime > 0 && '{{ $activeSource->provider }}' !== 'embed') {
                    this.showResumePrompt = true;
                } else if (this.video) {
                    // Try autoplay if browser allows
                    this.video.play().catch(e => {
                        console.log("Autoplay blocked");
                    });
                }
                
                if ('{{ $activeSource->provider }}' !== 'embed') {
                    this.startControlsTimer();
                }
                
                // Sync on visibility change
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden && this.isPlaying && '{{ $activeSource->provider }}' !== 'embed') {
                        this.saveProgress();
                    }
                });
            },
            
            onLoadedMetadata() {
                this.duration = this.video.duration;
            },
            
            onTimeUpdate() {
                if (!this.video) return;
                this.currentTime = this.video.currentTime;
                this.duration = this.video.duration || 0;
                
                if (this.duration > 0) {
                    this.progressPercentage = (this.currentTime / this.duration) * 100;
                }
                
                if (this.video.buffered.length > 0) {
                    this.bufferedPercentage = (this.video.buffered.end(this.video.buffered.length - 1) / this.duration) * 100;
                }
                
                // Periodic Sync every 10 seconds
                if (this.isAuth && this.isPlaying && Math.abs(this.currentTime - this.lastSyncTime) >= 10) {
                    this.saveProgress();
                }
                
                // Auto Next Episode prompt logic
                if (this.hasEpisodes && this.nextEpisodeUrl && this.duration > 0) {
                    const timeLeft = this.duration - this.currentTime;
                    if (timeLeft <= 15 && !this.showNextEpisodePrompt) {
                        this.triggerNextEpisodeCountdown();
                    }
                }
            },
            
            onEnded() {
                this.saveProgress();
                if (this.hasEpisodes && this.nextEpisodeUrl && !this.showNextEpisodePrompt) {
                    this.triggerNextEpisodeCountdown();
                }
            },
            
            togglePlay() {
                if (this.showResumePrompt || this.hasError) return;
                
                if (this.video.paused) {
                    this.video.play();
                } else {
                    this.video.pause();
                }
                
                this.showPlayAnimation = true;
                setTimeout(() => this.showPlayAnimation = false, 500);
            },
            
            handleKeydown(e) {
                // Ignore if user is typing
                const tag = e.target.tagName.toLowerCase();
                if (['input', 'textarea', 'select'].includes(tag) || e.target.isContentEditable) return;
                
                if (e.key === ' ' || e.code === 'Space') {
                    e.preventDefault();
                    this.togglePlay();
                } else if (e.key.toLowerCase() === 'f') {
                    e.preventDefault();
                    this.toggleFullscreen();
                } else if (e.key.toLowerCase() === 'm') {
                    e.preventDefault();
                    this.toggleMute();
                } else if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    if (this.video && this.duration > 0 && '{{ $activeSource->provider }}' !== 'embed') {
                        this.video.currentTime = Math.max(0, this.video.currentTime - 10);
                        this.showPlayAnimation = true;
                        setTimeout(() => this.showPlayAnimation = false, 500);
                    }
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    if (this.video && this.duration > 0 && '{{ $activeSource->provider }}' !== 'embed') {
                        this.video.currentTime = Math.min(this.duration, this.video.currentTime + 10);
                        this.showPlayAnimation = true;
                        setTimeout(() => this.showPlayAnimation = false, 500);
                    }
                }
            },
            
            seek(e) {
                const rect = e.currentTarget.getBoundingClientRect();
                const pos = (e.clientX - rect.left) / rect.width;
                this.video.currentTime = pos * this.duration;
            },
            
            updatePreviewTime(e) {
                const rect = e.currentTarget.getBoundingClientRect();
                const pos = (e.clientX - rect.left) / rect.width;
                this.previewPercentage = pos * 100;
                this.previewTime = pos * this.duration;
            },
            
            toggleMute() {
                this.video.muted = !this.video.muted;
                this.isMuted = this.video.muted;
                if (!this.isMuted && this.volume === 0) {
                    this.volume = 1;
                    this.video.volume = 1;
                }
            },
            
            updateVolume() {
                this.video.volume = this.volume;
                this.isMuted = this.volume == 0;
                this.video.muted = this.isMuted;
            },
            
            setSpeed(speed) {
                this.playbackRate = speed;
                this.video.playbackRate = speed;
            },
            
            toggleFullscreen() {
                const container = this.$refs.playerContainer;
                if (!document.fullscreenElement) {
                    container.requestFullscreen().catch(err => {
                        console.error(`Error attempting to enable fullscreen: ${err.message}`);
                    });
                } else {
                    document.exitFullscreen();
                }
            },
            
            showControls() {
                this.controlsVisible = true;
                this.startControlsTimer();
            },
            
            hideControls() {
                if (this.isPlaying) {
                    this.controlsVisible = false;
                }
            },
            
            startControlsTimer() {
                clearTimeout(this.controlsTimeout);
                this.controlsTimeout = setTimeout(() => {
                    this.hideControls();
                }, 3000);
            },
            
            formatTime(seconds) {
                if (!seconds || isNaN(seconds)) return '0:00';
                const h = Math.floor(seconds / 3600);
                const m = Math.floor((seconds % 3600) / 60);
                const s = Math.floor(seconds % 60);
                if (h > 0) return `${h}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                return `${m}:${s.toString().padStart(2, '0')}`;
            },
            
            // Resume Logic
            resumePlayback() {
                this.showResumePrompt = false;
                this.video.currentTime = this.resumeTime;
                this.video.play().catch(e => console.log(e));
            },
            
            startFromBeginning() {
                this.showResumePrompt = false;
                this.video.currentTime = 0;
                this.video.play().catch(e => console.log(e));
            },
            
            retry() {
                this.hasError = false;
                this.isLoading = true;
                this.video.load();
                this.video.play().catch(e => console.log(e));
            },
            
            // History Sync API
            saveProgress() {
                if (!this.isAuth || this.duration === 0 || '{{ $activeSource->provider }}' === 'embed') return;
                
                // Ensure we don't send time larger than duration
                const safeProgress = Math.min(Math.floor(this.currentTime), Math.floor(this.duration));
                
                this.lastSyncTime = safeProgress;
                
                fetch('{{ route('watch-history.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        content_id: this.contentId,
                        episode_id: this.episodeId,
                        progress_seconds: safeProgress,
                        duration_seconds: Math.floor(this.duration)
                    })
                }).catch(e => console.error("Failed to save progress", e));
            },
            
            // Auto Next Logic
            triggerNextEpisodeCountdown() {
                this.showNextEpisodePrompt = true;
                this.nextEpisodeCountdown = 5;
                
                clearInterval(this.nextEpisodeTimer);
                this.nextEpisodeTimer = setInterval(() => {
                    this.nextEpisodeCountdown--;
                    if (this.nextEpisodeCountdown <= 0) {
                        this.goToNextEpisode();
                    }
                }, 1000);
            },
            
            cancelNextEpisode() {
                this.showNextEpisodePrompt = false;
                clearInterval(this.nextEpisodeTimer);
            },
            
            goToNextEpisode() {
                if (this.nextEpisodeUrl) {
                    window.location.href = this.nextEpisodeUrl;
                }
            }
        };
    }
</script>
@endif
@endsection
