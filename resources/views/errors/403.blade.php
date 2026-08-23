@extends('layouts.public')

@section('title', 'Forbidden - NontonKu')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center space-y-8">
        <div>
            <h1 class="text-9xl font-extrabold text-red-500 tracking-tighter">403</h1>
            <h2 class="mt-4 text-3xl font-bold text-gray-900 dark:text-white">Access Denied</h2>
            <p class="mt-2 text-lg text-gray-600 dark:text-zinc-400">
                You do not have permission to access this page or resource.
            </p>
        </div>
        <div class="flex items-center justify-center gap-4">
            <button onclick="window.history.back()" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-zinc-700 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Go Back
            </button>
            <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Return Home
            </a>
        </div>
    </div>
</div>
@endsection
