@props(['animated' => false])

<svg viewBox="0 0 220 90" preserveAspectRatio="none" aria-hidden="true" focusable="false"
    {{ $attributes->class(['tratto-animato' => $animated]) }}>
    <path pathLength="1"
        d="M150 8 C 90 2, 18 14, 8 44 C 0 74, 80 86, 140 82 C 200 78, 218 52, 208 30 C 198 10, 140 4, 96 10"
        fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" />
</svg>
