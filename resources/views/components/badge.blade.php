@props(['type' => 'default'])

@php
$classes = [
    'default' => 'bg-gray-500/10 text-gray-300 border-gray-500/20',
    'primary' => 'bg-primary-500/10 text-primary-300 border-primary-500/20',
    'success' => 'bg-green-500/10 text-green-300 border-green-500/20',
    'warning' => 'bg-yellow-500/10 text-yellow-300 border-yellow-500/20',
    'danger' => 'bg-red-500/10 text-red-300 border-red-500/20',
    'info' => 'bg-blue-500/10 text-blue-300 border-blue-500/20',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border ' . ($classes[$type] ?? $classes['default'])]) }}>
    {{ $slot }}
</span>
