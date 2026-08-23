@props(['type' => 'info'])

@php
$classes = match($type) {
    'success' => 'bg-green-50 text-green-800 dark:bg-green-900/20 dark:text-green-400 border-green-200 dark:border-green-900/50',
    'warning' => 'bg-yellow-50 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400 border-yellow-200 dark:border-yellow-900/50',
    'danger' => 'bg-red-50 text-red-800 dark:bg-red-900/20 dark:text-red-400 border-red-200 dark:border-red-900/50',
    default => 'bg-indigo-50 text-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-400 border-indigo-200 dark:border-indigo-900/50',
};
@endphp

<div {{ $attributes->merge(['class' => "p-4 rounded-lg border text-sm $classes"]) }} role="alert">
    {{ $slot }}
</div>
