<x-admin-layout>
    <x-slot name="header">
        Genres
    </x-slot>

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <form action="{{ route('admin.genres.index') }}" method="GET" class="w-full sm:w-auto flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search genres..." class="w-full sm:w-64 rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-md font-semibold text-xs text-gray-700 dark:text-zinc-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition ease-in-out duration-150">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.genres.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-zinc-800 border border-transparent rounded-md font-semibold text-xs text-gray-600 dark:text-zinc-400 uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-zinc-700 transition ease-in-out duration-150">
                    Clear
                </a>
            @endif
        </form>

        <a href="{{ route('admin.genres.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition ease-in-out duration-150 shadow-sm">
            Add Genre
        </a>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-800">
                <thead class="bg-gray-50 dark:bg-zinc-950">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Slug</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Content Count</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-800">
                    @forelse($genres as $genre)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $genre->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-zinc-400">{{ $genre->slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-zinc-400">{{ $genre->contents()->count() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.genres.edit', $genre) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Edit</a>
                                    
                                    <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this genre?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-zinc-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">No genres found</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">Get started by creating a new genre.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($genres->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-800">
                {{ $genres->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
