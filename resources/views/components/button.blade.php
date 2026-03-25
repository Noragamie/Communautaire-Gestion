@props(['variant' => 'primary', 'size' => 'md', 'icon' => null])

@php
$variants = [
    'primary' => 'btn-primary',
    'secondary' => 'btn-secondary',
    'ghost' => 'px-6 py-3 rounded-xl font-semibold text-gray-300 hover:text-white hover:bg-white/5 transition-all duration-300',
    'danger' => 'px-6 py-3 rounded-xl font-semibold text-white bg-red-500 hover:bg-red-600 transition-all duration-300',
];

$sizes = [
    'sm' => 'px-4 py-2 text-sm',
    'md' => 'px-6 py-3 text-base',
    'lg' => 'px-8 py-4 text-lg',
];
@endphp

<button {{ $attributes->merge(['class' => $variants[$variant] . ' ' . $sizes[$size]]) }}>
    @if($icon)
        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
    @endif
    {{ $slot }}
</button>
