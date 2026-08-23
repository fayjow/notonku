@extends('layouts.public')

@section('title', 'Watch History - NontonKu')

@section('content')
    <x-container class="py-8">
        <nav aria-label="Breadcrumb" class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-zinc-400">
                <li><a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a></li>
                <li><span class="mx-1">/</span></li>
                <li><a href="{{ route('profile.edit') }}" class="hover:text-indigo-600 transition-colors">Profile</a></li>
                <li><span class="mx-1">/</span></li>
                <li aria-current="page" class="text-gray-900 dark:text-white font-medium">History</li>
            </ol>
        </nav>

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                Watch History
            </h1>
        </div>

        @if($histories->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <svg class="h-16 w-16 text-gray-300 dark:text-zinc-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">No history yet</h3>
                <p class="mt-1 text-gray-500 dark:text-zinc-400">You haven't watched anything yet.</p>
                <div class="mt-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Start Watching
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach($histories as $history)
                    <div class="flex items-center bg-white dark:bg-zinc-900 rounded-lg shadow border border-gray-200 dark:border-zinc-800 p-4">
                        <div class="flex-shrink-0 h-24 w-16 md:h-32 md:w-24 bg-gray-200 dark:bg-zinc-800 rounded overflow-hidden">
                            @if($history->content->poster_url)
                                <img src="{{ url($history->content->poster_url) }}" alt="{{ $history->content->title }}" class="h-full w-full object-cover">
                            @endif
                        </div>
                        <div class="ml-4 flex-1">
                            @php
                                $routeName = $history->content->type->value . 's.show';
                                if ($history->content->type->value === 'anime') $routeName = 'anime.show';
                                if ($history->content->type->value === 'donghua') $routeName = 'donghua.show';
                                if ($history->content->type->value === 'series') $routeName = 'series.show';
                            @endphp
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                                <a href="{{ route($routeName, $history->content->slug) }}" class="hover:text-indigo-600">
                                    {{ $history->content->title }}
                                </a>
                            </h4>
                            
                            @if($history->episode)
                                <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">
                                    Season {{ $history->episode->season->season_number }} Episode {{ $history->episode->episode_number }}: {{ $history->episode->title }}
                                </p>
                            @endif
                            
                            <div class="mt-3 flex items-center">
                                <div class="w-full bg-gray-200 dark:bg-zinc-700 rounded-full h-2.5 max-w-md">
                                    @php
                                        $percentage = 0;
                                        if ($history->duration_seconds > 0) {
                                            $percentage = min(100, round(($history->progress_seconds / $history->duration_seconds) * 100));
                                        } elseif ($history->is_completed) {
                                            $percentage = 100;
                                        }
                                    @endphp
                                    <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-700 dark:text-zinc-300">{{ $percentage }}%</span>
                            </div>
                            
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-xs text-gray-400 dark:text-zinc-500">
                                    Last watched {{ $history->last_watched_at->diffForHumans() }}
                                </p>
                                
                                <form action="{{ route('history.destroy', $history->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-medium text-gray-400 hover:text-red-500 transition-colors" title="Remove from history">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $histories->links() }}
            </div>
        @endif
    </x-container>
@endsection
