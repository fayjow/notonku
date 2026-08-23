@extends('layouts.public')

@section('title', 'My Ratings - NontonKu')

@section('content')
<div class="py-12">
    <x-container>
        <nav aria-label="Breadcrumb" class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-zinc-400">
                <li><a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a></li>
                <li><span class="mx-1">/</span></li>
                <li><a href="{{ route('profile.edit') }}" class="hover:text-indigo-600 transition-colors">Profile</a></li>
                <li><span class="mx-1">/</span></li>
                <li aria-current="page" class="text-gray-900 dark:text-white font-medium">Ratings</li>
            </ol>
        </nav>

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                My Ratings
            </h1>
            <p class="text-gray-500 dark:text-zinc-400 mt-2">Content you have rated.</p>
        </div>

        @if($ratings->isEmpty())
            <x-empty-state title="No ratings yet" description="You haven't rated any movies or series yet." />
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                @foreach($ratings as $rating)
                    @php
                        $content = $rating->content;
                    @endphp
                    <div class="relative group">
                        <x-content-card :content="$content" />
                        <div class="absolute top-2 left-2 bg-indigo-600/90 backdrop-blur text-white text-xs font-bold px-2 py-1 rounded shadow-sm z-10">
                            You: {{ $rating->rating }}/10
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-12">
                {{ $ratings->links() }}
            </div>
        @endif
    </x-container>
</div>
@endsection
