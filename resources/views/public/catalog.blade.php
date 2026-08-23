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
