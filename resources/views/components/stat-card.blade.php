@props(['title', 'value', 'icon', 'trend' => null, 'color' => 'primary'])

@php
$colors = [
    'primary' => 'from-primary-500 to-primary-600',
    'accent' => 'from-accent-500 to-accent-600',
    'success' => 'from-green-500 to-green-600',
    'warning' => 'from-yellow-500 to-yellow-600',
];
@endphp

<div class="glass-effect rounded-2xl p-6 hover-lift">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <p class="text-gray-400 text-sm font-medium mb-2">{{ $title }}</p>
            <p class="text-3xl font-bold text-white mb-1">{{ $value }}</p>
            @if($trend)
                <p class="text-sm {{ $trend > 0 ? 'text-green-400' : 'text-red-400' }}">
                    {{ $trend > 0 ? '+' : '' }}{{ $trend }}% ce mois
                </p>
            @endif
        </div>
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $colors[$color] }} flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $icon !!}
            </svg>
        </div>
    </div>
</div>
