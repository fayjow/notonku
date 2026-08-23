<x-admin-layout>
    <x-slot name="header">
        Edit Video Source
        @if($sourceable)
            - 
            @if($sourceable instanceof \App\Models\Content)
                {{ $sourceable->title }}
            @elseif($sourceable instanceof \App\Models\Episode)
                {{ $sourceable->season->content->title }} - S{{ $sourceable->season->season_number }}E{{ $sourceable->episode_number }}
            @endif
        @endif
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.video-sources.index', ['sourceable_type' => $videoSource->sourceable_type, 'sourceable_id' => $videoSource->sourceable_id]) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Sources
        </a>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 overflow-hidden max-w-2xl">
        <form action="{{ route('admin.video-sources.update', $videoSource) }}" method="POST" class="p-6" x-data="{ provider: '{{ old('provider', $videoSource->provider) }}' }">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Provider (Source Type) -->
                    <div>
                        <label for="provider" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Source Type</label>
                        <select name="provider" id="provider" required x-model="provider"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="mp4">MP4 (Direct Video)</option>
                            <option value="hls">HLS (.m3u8 Stream)</option>
                            <option value="embed">Embed (Iframe)</option>
                        </select>
                        @error('provider')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Server Name -->
                    <div>
                        <label for="server_name" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Server Name</label>
                        <input type="text" name="server_name" id="server_name" value="{{ old('server_name', $videoSource->server_name) }}" required placeholder="e.g. Server 1, VIP Server"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('server_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- URL -->
                <div>
                    <label for="url" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">URL</label>
                    <input type="url" name="url" id="url" value="{{ old('url', $videoSource->url) }}" required placeholder="https://..."
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('url')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Quality -->
                    <div>
                        <label for="quality" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Quality (Optional)</label>
                        <input type="text" name="quality" id="quality" value="{{ old('quality', $videoSource->quality) }}" placeholder="e.g. 1080p, 720p, 4K"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('quality')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Language -->
                <div>
                    <label for="language" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Language/Subtitle (Optional)</label>
                    <input type="text" name="language" id="language" value="{{ old('language', $videoSource->language) }}" placeholder="e.g. English Sub, Indonesia Sub, Dubbed"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('language')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2 flex gap-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $videoSource->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-zinc-900 dark:border-zinc-700">
                        <span class="ml-2 text-sm text-gray-700 dark:text-zinc-300">Active (Visible to users)</span>
                    </label>
                    <label class="flex items-center" :class="{ 'opacity-50': provider !== 'mp4' }">
                        <input type="checkbox" name="is_downloadable" value="1" x-bind:disabled="provider !== 'mp4'" {{ old('is_downloadable', $videoSource->is_downloadable) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-zinc-900 dark:border-zinc-700 disabled:opacity-50">
                        <span class="ml-2 text-sm text-gray-700 dark:text-zinc-300">Allow Download</span>
                    </label>
                    <label class="flex items-center" :class="{ 'opacity-50': provider !== 'embed' }">
                        <input type="checkbox" name="supports_autoplay" value="1" x-bind:disabled="provider !== 'embed'" {{ old('supports_autoplay', $videoSource->supports_autoplay) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-zinc-900 dark:border-zinc-700 disabled:opacity-50">
                        <span class="ml-2 text-sm text-gray-700 dark:text-zinc-300">Supports Autoplay (Embed Only)</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-zinc-800">
                <a href="{{ route('admin.video-sources.index', ['sourceable_type' => $videoSource->sourceable_type, 'sourceable_id' => $videoSource->sourceable_id]) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-md font-semibold text-xs text-gray-700 dark:text-zinc-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition ease-in-out duration-150">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition ease-in-out duration-150 shadow-sm">
                    Update Source
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
