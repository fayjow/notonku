<x-admin-layout>
    <x-slot name="header">
        Episodes - Season {{ $season->season_number }} - {{ $content->title }}
    </x-slot>

    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <a href="{{ route('admin.content.seasons.index', $content) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Seasons
        </a>

        <a href="{{ route('admin.content.seasons.episodes.create', [$content, $season]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition ease-in-out duration-150 shadow-sm whitespace-nowrap">
            Add Episode
        </a>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-800">
                <thead class="bg-gray-50 dark:bg-zinc-950">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider w-20">Thumbnail</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Ep Number</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-800">
                    @forelse($episodes as $episode)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($episode->thumbnail_url)
                                    <img src="{{ url($episode->thumbnail_url) }}" alt="Ep {{ $episode->episode_number }}" class="h-12 w-20 object-cover rounded shadow-sm bg-gray-200 dark:bg-zinc-800">
                                @else
                                    <div class="h-12 w-20 rounded shadow-sm bg-gray-200 dark:bg-zinc-800 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">Episode {{ $episode->episode_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $episode->title ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500 dark:text-zinc-400">{{ $episode->duration_minutes ? $episode->duration_minutes . ' min' : '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($episode->is_published)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">Published</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.video-sources.index', ['sourceable_type' => $episode->getMorphClass(), 'sourceable_id' => $episode->id]) }}" class="text-orange-600 dark:text-orange-400 hover:text-orange-900 dark:hover:text-orange-300">Video Sources</a>
                                    
                                    <a href="{{ route('admin.content.seasons.episodes.edit', [$content, $season, $episode]) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Edit</a>
                                    
                                    <form action="{{ route('admin.content.seasons.episodes.destroy', [$content, $season, $episode]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this episode? This will delete all its video sources.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-zinc-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">No episodes found</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">Get started by adding a new episode.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($episodes->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-800">
                {{ $episodes->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
