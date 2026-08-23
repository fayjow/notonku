<x-admin-layout>
    <x-slot name="header">
        Edit Episode {{ $episode->episode_number }}
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.content.seasons.episodes.index', [$content, $season]) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Episodes
        </a>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 overflow-hidden max-w-2xl">
        <form action="{{ route('admin.content.seasons.episodes.update', [$content, $season, $episode]) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Episode Number -->
                <div>
                    <label for="episode_number" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Episode Number</label>
                    <input type="number" min="1" name="episode_number" id="episode_number" value="{{ old('episode_number', $episode->episode_number) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('episode_number')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Title (Optional)</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $episode->title) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <p class="mt-1 text-xs text-gray-500 dark:text-zinc-500">Leave blank to just show "Episode X".</p>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $episode->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration_minutes" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Duration (Minutes)</label>
                    <input type="number" min="1" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes', $episode->duration_minutes) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('duration_minutes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Thumbnail -->
                <div>
                    <label for="thumbnail" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Thumbnail Image</label>
                    @if($episode->thumbnail_url)
                        <div class="mt-2 mb-3">
                            <img src="{{ ($episode->thumbnail_url) }}" alt="Current Thumbnail" class="w-48 rounded shadow-sm">
                        </div>
                    @endif
                    <input type="file" name="thumbnail" id="thumbnail" accept="image/jpeg, image/png, image/webp" 
                           class="mt-1 block w-full text-sm text-gray-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400">
                    <p class="mt-1 text-xs text-gray-500 dark:text-zinc-500">Upload new to replace. Max 2MB. 16:9 horizontal orientation recommended.</p>
                    @error('thumbnail')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="pt-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $episode->is_published) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-zinc-900 dark:border-zinc-700">
                        <span class="ml-2 text-sm text-gray-700 dark:text-zinc-300">Published (Visible to public)</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-zinc-800">
                <a href="{{ route('admin.content.seasons.episodes.index', [$content, $season]) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-md font-semibold text-xs text-gray-700 dark:text-zinc-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition ease-in-out duration-150">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition ease-in-out duration-150 shadow-sm">
                    Update Episode
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
