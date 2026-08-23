<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Dashboard Stats & Links -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <a href="{{ route('favorites.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['favorites_count'] }}</div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Favorites</div>
                </a>
                <a href="{{ route('watchlist.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['watchlist_count'] }}</div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Watchlist</div>
                </a>
                <a href="{{ route('ratings.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['ratings_count'] }}</div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Ratings</div>
                </a>
                <a href="{{ route('history.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['episodes_watched'] }}</div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Episodes</div>
                </a>
                <a href="{{ route('history.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['movies_watched'] }}</div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Movies</div>
                </a>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
