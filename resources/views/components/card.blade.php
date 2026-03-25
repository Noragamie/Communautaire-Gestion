@props(['hover' => true, 'gradient' => false])

<div {{ $attributes->merge(['class' => 'rounded-2xl p-6 transition-all duration-300 ' . 
    ($gradient ? 'gradient-border' : 'glass-effect') . 
    ($hover ? ' hover-lift cursor-pointer' : '')]) }}>
    {{ $slot }}
</div>
