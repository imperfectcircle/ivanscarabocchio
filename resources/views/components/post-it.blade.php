@props(['color' => 'giallo'])

@php
    $colorClasses = [
        'giallo' => 'bg-postit-giallo',
        'rosa' => 'bg-postit-rosa',
        'verde' => 'bg-postit-verde',
        'azzurro' => 'bg-postit-azzurro',
    ];
@endphp

<div
    {{ $attributes->class([$colorClasses[$color] ?? $colorClasses['giallo'], 'p-5 shadow-[2px_8px_14px_-6px_rgb(29_36_64/0.35)]']) }}>
    {{ $slot }}
</div>
