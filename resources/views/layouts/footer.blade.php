<footer class="bg-white dark:bg-zinc-900 border-t border-gray-200 dark:border-zinc-800 mt-auto py-8">
    <x-container>
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex justify-center md:justify-start mb-6 md:mb-0">
                <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400 tracking-tight">NontonKu</span>
                <span class="ml-2 text-sm text-gray-500 dark:text-zinc-400 self-center">A modern streaming platform</span>
            </div>
            
            <div class="flex flex-wrap justify-center space-x-6 text-sm text-gray-500 dark:text-zinc-400">
                <a href="{{ route('home') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">Home</a>
                <a href="{{ route('movies') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">Movies</a>
                <a href="{{ route('series') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">Series</a>
                <a href="{{ route('anime') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">Anime</a>
            </div>
        </div>
        
        <div class="mt-8 border-t border-gray-200 dark:border-zinc-800 pt-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <p class="mt-8 text-base text-gray-400 md:mt-0 text-center md:text-left">
                &copy; {{ date('Y') }} NontonKu. All rights reserved.
            </p>
        </div>
    </x-container>
</footer>
