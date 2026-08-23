@props(['title', 'actionText' => null, 'actionUrl' => null])

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $title }}</h2>
    @if($actionText && $actionUrl)
        <a href="{{ $actionUrl }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 focus:outline-none focus:underline transition-colors">
            {{ $actionText }} <span aria-hidden="true">&rarr;</span>
        </a>
    @endif
</div>
