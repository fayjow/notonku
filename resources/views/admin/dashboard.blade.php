<x-admin-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Content Stat -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 p-6 flex items-center">
            <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Total Content</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_content'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Users Stat -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 p-6 flex items-center">
            <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center text-green-600 dark:text-green-400 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Total Users</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_users'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Video Sources Stat -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 p-6 flex items-center">
            <div class="w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900/50 flex items-center justify-center text-orange-600 dark:text-orange-400 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Video Sources</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_sources'] ?? 0 }}</p>
            </div>
        </div>
        
        <!-- Episodes Stat -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 p-6 flex items-center">
            <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Episodes</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_episodes'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Content Types -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-zinc-800">
            <h3 class="font-semibold text-gray-900 dark:text-white">Content by Type</h3>
        </div>
        <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-gray-50 dark:bg-zinc-950 rounded-lg">
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $stats['movies'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Movies</p>
            </div>
            <div class="text-center p-4 bg-gray-50 dark:bg-zinc-950 rounded-lg">
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $stats['series'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Series</p>
            </div>
            <div class="text-center p-4 bg-gray-50 dark:bg-zinc-950 rounded-lg">
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $stats['anime'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Anime</p>
            </div>
            <div class="text-center p-4 bg-gray-50 dark:bg-zinc-950 rounded-lg">
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $stats['donghua'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Donghua</p>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <!-- Recent Content -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-zinc-800 flex justify-between items-center">
                <h3 class="font-semibold text-gray-900 dark:text-white">Recently Added Content</h3>
                <a href="{{ route('admin.content.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-zinc-950 dark:text-gray-400 border-b border-gray-100 dark:border-zinc-800">
                        <tr>
                            <th class="px-6 py-3 font-medium">Title</th>
                            <th class="px-6 py-3 font-medium text-center">Type</th>
                            <th class="px-6 py-3 font-medium text-right">Added</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                        @forelse($recentContent as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-6 py-3 font-medium text-gray-900 dark:text-white truncate max-w-[200px]">{{ $item->title }}</td>
                                <td class="px-6 py-3 text-center"><span class="px-2 py-1 rounded text-xs capitalize bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">{{ $item->type->value }}</span></td>
                                <td class="px-6 py-3 text-gray-500 dark:text-zinc-400 text-right whitespace-nowrap">{{ $item->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-zinc-400">No content found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-zinc-800 flex justify-between items-center">
                <h3 class="font-semibold text-gray-900 dark:text-white">Recent Users</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-zinc-950 dark:text-gray-400 border-b border-gray-100 dark:border-zinc-800">
                        <tr>
                            <th class="px-6 py-3 font-medium">Name</th>
                            <th class="px-6 py-3 font-medium text-right">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                        @forelse($recentUsers as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-6 py-3 font-medium text-gray-900 dark:text-white truncate max-w-[200px]">{{ $user->name }}</td>
                                <td class="px-6 py-3 text-gray-500 dark:text-zinc-400 text-right whitespace-nowrap">{{ $user->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center text-gray-500 dark:text-zinc-400">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
