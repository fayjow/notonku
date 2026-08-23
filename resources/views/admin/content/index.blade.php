<x-admin-layout>
    <x-slot name="header">
        Content Management
    </x-slot>

    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <form action="{{ route('admin.content.index') }}" method="GET" class="w-full md:w-auto flex flex-wrap gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title..." class="w-full sm:w-48 rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
            
            <select name="type" class="w-full sm:w-32 rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                <option value="">All Types</option>
                <option value="movie" {{ request('type') === 'movie' ? 'selected' : '' }}>Movies</option>
                <option value="series" {{ request('type') === 'series' ? 'selected' : '' }}>Series</option>
                <option value="anime" {{ request('type') === 'anime' ? 'selected' : '' }}>Anime</option>
                <option value="donghua" {{ request('type') === 'donghua' ? 'selected' : '' }}>Donghua</option>
            </select>

            <select name="status" class="w-full sm:w-32 rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                <option value="">All Status</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>

            <button type="submit" class="inline-flex items-center px-4 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-md font-semibold text-xs text-gray-700 dark:text-zinc-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition ease-in-out duration-150">
                Filter
            </button>
            @if(request('search') || request('type') || request('status'))
                <a href="{{ route('admin.content.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-zinc-800 border border-transparent rounded-md font-semibold text-xs text-gray-600 dark:text-zinc-400 uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-zinc-700 transition ease-in-out duration-150">
                    Clear
                </a>
            @endif
        </form>

        <a href="{{ route('admin.content.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition ease-in-out duration-150 shadow-sm whitespace-nowrap">
            Add Content
        </a>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-800">
                <thead class="bg-gray-50 dark:bg-zinc-950">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider w-16">Poster</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Visibility</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-800">
                    @forelse($contents as $content)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($content->poster_url)
                                    <img src="{{ url($content->poster_url) }}" alt="{{ $content->title }}" class="h-12 w-8 object-cover rounded shadow-sm bg-gray-200 dark:bg-zinc-800">
                                @else
                                    <div class="h-12 w-8 rounded shadow-sm bg-gray-200 dark:bg-zinc-800 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap min-w-[200px]">
                                <div class="text-sm font-medium text-gray-900 dark:text-white truncate" title="{{ $content->title }}">{{ Str::limit($content->title, 40) }}</div>
                                <div class="text-xs text-gray-500 dark:text-zinc-400">{{ $content->release_date?->format('Y') ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                    {{ ucfirst($content->type->value) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ ucfirst($content->status->value) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($content->is_published)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">Published</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    @if(in_array($content->type->value, ['series', 'anime', 'donghua']))
                                        <a href="{{ route('admin.content.seasons.index', $content) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">Seasons</a>
                                    @endif
                                    
                                    @if($content->type->value === 'movie')
                                        <a href="{{ route('admin.video-sources.index', ['sourceable_type' => $content->getMorphClass(), 'sourceable_id' => $content->id]) }}" class="text-orange-600 dark:text-orange-400 hover:text-orange-900 dark:hover:text-orange-300">Video Sources</a>
                                    @endif
                                    
                                    <a href="{{ route('admin.content.edit', $content) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Edit</a>
                                    
                                    <form action="{{ route('admin.content.destroy', $content) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this content? This will also delete all associated seasons, episodes, video sources, ratings, and history.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-zinc-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">No content found</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">Get started by adding some content.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($contents->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-800">
                {{ $contents->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
