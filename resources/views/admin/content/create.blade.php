<x-admin-layout>
    <x-slot name="header">
        Create Content
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.content.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Content List
        </a>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 overflow-hidden">
        <form action="{{ route('admin.content.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Main Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Original Title -->
                    <div>
                        <label for="original_title" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Original Title (Optional)</label>
                        <input type="text" name="original_title" id="original_title" value="{{ old('original_title') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Description</label>
                        <textarea name="description" id="description" rows="5"
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Release Date -->
                        <div>
                            <label for="release_date" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Release Date</label>
                            <input type="date" name="release_date" id="release_date" value="{{ old('release_date') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <!-- Duration -->
                        <div>
                            <label for="duration_minutes" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Duration (Minutes)</label>
                            <input type="number" min="1" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        
                        <!-- Age Rating -->
                        <div>
                            <label for="age_rating" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Age Rating</label>
                            <input type="text" name="age_rating" id="age_rating" value="{{ old('age_rating') }}" placeholder="e.g. PG-13, R"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- Sidebar Details -->
                <div class="space-y-6">
                    
                    <!-- Publishing & Status -->
                    <div class="bg-gray-50 dark:bg-zinc-950 p-4 rounded-lg border border-gray-200 dark:border-zinc-800 space-y-4">
                        <h4 class="font-medium text-gray-900 dark:text-white">Publishing & Status</h4>
                        
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Type</label>
                            <select name="type" id="type" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach($types as $type)
                                    <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>{{ ucfirst($type->value) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Status</label>
                            <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach($statuses as $status)
                                    <option value="{{ $status->value }}" {{ old('status') == $status->value ? 'selected' : '' }}>{{ ucfirst($status->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="pt-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-zinc-900 dark:border-zinc-700">
                                <span class="ml-2 text-sm text-gray-700 dark:text-zinc-300">Published (Visible to public)</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-zinc-900 dark:border-zinc-700">
                                <span class="ml-2 text-sm text-gray-700 dark:text-zinc-300">Featured Content</span>
                            </label>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="bg-gray-50 dark:bg-zinc-950 p-4 rounded-lg border border-gray-200 dark:border-zinc-800 space-y-4">
                        <h4 class="font-medium text-gray-900 dark:text-white">Images</h4>
                        
                        <div>
                            <label for="poster" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Poster Image</label>
                            <input type="file" name="poster" id="poster" accept="image/jpeg, image/png, image/webp" 
                                   class="mt-1 block w-full text-sm text-gray-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400">
                            <p class="mt-1 text-xs text-gray-500 dark:text-zinc-500">Max 2MB. Vertical orientation recommended.</p>
                            @error('poster')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="backdrop" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Backdrop Image</label>
                            <input type="file" name="backdrop" id="backdrop" accept="image/jpeg, image/png, image/webp" 
                                   class="mt-1 block w-full text-sm text-gray-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400">
                            <p class="mt-1 text-xs text-gray-500 dark:text-zinc-500">Max 2MB. 16:9 horizontal orientation recommended.</p>
                            @error('backdrop')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Genres -->
                    <div class="bg-gray-50 dark:bg-zinc-950 p-4 rounded-lg border border-gray-200 dark:border-zinc-800">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Genres</h4>
                        <div class="max-h-48 overflow-y-auto space-y-2 p-2 border border-gray-200 dark:border-zinc-700 rounded bg-white dark:bg-zinc-900 custom-scrollbar">
                            @foreach($genres as $genre)
                                <label class="flex items-center">
                                    <input type="checkbox" name="genres[]" value="{{ $genre->id }}" {{ in_array($genre->id, old('genres', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-zinc-800 dark:border-zinc-600">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-zinc-300">{{ $genre->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-zinc-800">
                <a href="{{ route('admin.content.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-md font-semibold text-xs text-gray-700 dark:text-zinc-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition ease-in-out duration-150">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition ease-in-out duration-150 shadow-sm">
                    Create Content
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('title').addEventListener('input', function() {
            if (!document.getElementById('slug').value || document.getElementById('slug').dataset.auto == 'true') {
                let slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
                let slugInput = document.getElementById('slug');
                slugInput.value = slug;
                slugInput.dataset.auto = 'true';
            }
        });
        document.getElementById('slug').addEventListener('input', function() {
            this.dataset.auto = 'false';
        });
    </script>
</x-admin-layout>
