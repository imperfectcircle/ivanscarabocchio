@props([
    'id',
    'title',
    'kicker' => null,
    'link' => null,
    'linkLabel' => null,
])

<div {{ $attributes->class('mb-10 flex flex-wrap items-end justify-between gap-x-8 gap-y-4') }}>
    <div>
        @if ($kicker)
            <p class="font-hand text-pennarello -rotate-1 text-2xl">{{ $kicker }}</p>
        @endif
        <h2 id="{{ $id }}" class="font-hand relative inline-block text-5xl leading-none md:text-6xl">
            {{ $title }}
            <x-doodle.underline class="text-biro absolute -bottom-3 left-0 h-4 w-full" />
        </h2>
    </div>

    @if ($link)
        <a href="{{ $link }}" class="link-scarabocchio font-hand text-2xl">{{ $linkLabel }} &rarr;</a>
    @endif
</div>
