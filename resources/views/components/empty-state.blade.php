@props(['title', 'description' => null])

<div class="text-center py-12 px-4 border-2 border-dashed border-gray-300 dark:border-zinc-800 rounded-xl">
    <div class="mx-auto w-12 h-12 text-gray-400 dark:text-zinc-600 mb-4">
        {{ $icon ?? '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>' }}
    </div>
    <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
    @if($description)
        <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">{{ $description }}</p>
    @endif
    <div class="mt-6">
        {{ $slot }}
    </div>
</div>
