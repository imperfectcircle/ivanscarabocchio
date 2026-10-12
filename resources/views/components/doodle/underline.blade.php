@props(['animated' => false])

<svg viewBox="0 0 300 20" preserveAspectRatio="none" aria-hidden="true" focusable="false"
    {{ $attributes->class(['tratto-animato' => $animated]) }}>
    <path pathLength="1" d="M3 13 C 55 5, 110 17, 165 10 S 255 5, 297 12" fill="none" stroke="currentColor"
        stroke-width="4" stroke-linecap="round" />
</svg>
