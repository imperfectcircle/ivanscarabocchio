@props([
    'title' => null,
    'description' => 'Il taccuino di Ivan Scarabocchio: libri, racconti, diario ed eventi.',
])

@php
    $navigationItems = [
        ['label' => 'Libri', 'href' => '#libro'],
        ['label' => 'Racconti', 'href' => '#racconti'],
        ['label' => 'Diario', 'href' => '#diario'],
        ['label' => 'Eventi', 'href' => '#eventi'],
        ['label' => 'Chi sono', 'href' => '#'],
        ['label' => 'Contatti', 'href' => '#contatti'],
    ];
@endphp

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title . ' · Ivan Scarabocchio' : 'Ivan Scarabocchio · Scrittore' }}</title>
    <meta name="description" content="{{ $description }}">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen antialiased">
    <a href="#contenuto"
        class="bg-postit-giallo font-hand sr-only z-50 px-4 py-2 text-xl focus:not-sr-only focus:fixed focus:left-4 focus:top-4">
        Vai al contenuto
    </a>

    <header class="relative z-20">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-5 py-5">
            <a href="{{ route('home') }}" class="font-hand group relative text-3xl leading-none md:text-4xl">
                Ivan Scarabocchio
                <x-doodle.underline
                    class="text-pennarello absolute -bottom-2 left-0 h-3 w-full transition-transform group-hover:-rotate-2" />
            </a>

            <button type="button" data-menu-toggle aria-controls="menu-principale" aria-expanded="false"
                class="bottone bg-foglio md:hidden">
                Menu
            </button>

            <nav id="menu-principale" aria-label="Menu principale" class="w-full max-md:hidden md:w-auto">
                <ul class="font-hand flex flex-col gap-1 pb-2 text-2xl md:flex-row md:gap-6 md:pb-0">
                    @foreach ($navigationItems as $item)
                        <li>
                            <a href="{{ $item['href'] }}"
                                class="hover:text-biro inline-flex min-h-11 items-center decoration-wavy decoration-2 underline-offset-4 hover:underline">
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </header>

    <main id="contenuto">
        {{ $slot }}
    </main>

    <footer id="contatti" class="bg-inchiostro text-carta mt-24">
        <div class="mx-auto grid max-w-6xl gap-10 px-5 py-14 md:grid-cols-3">
            <div>
                <p class="font-hand text-4xl">Grazie della visita!</p>
                <p class="text-carta/80 mt-3">Torna a trovarmi: il taccuino si riempie un po' alla volta.</p>
            </div>

            <div>
                <h2 class="font-hand text-postit-giallo text-3xl">Scrivimi</h2>
                <ul class="mt-3 space-y-2">
                    <li>
                        <span class="text-carta/80">Email:</span>
                        <span>ciao [at] esempio [punto] it</span>
                    </li>
                    <li><a href="#" class="decoration-postit-rosa hover:text-postit-giallo underline decoration-wavy underline-offset-4">Instagram</a></li>
                    <li><a href="#" class="decoration-postit-rosa hover:text-postit-giallo underline decoration-wavy underline-offset-4">Facebook</a></li>
                </ul>
            </div>

            <div>
                <h2 class="font-hand text-postit-giallo text-3xl">Altre pagine</h2>
                <ul class="mt-3 space-y-2">
                    <li><a href="#rassegna-stampa" class="decoration-postit-rosa hover:text-postit-giallo underline decoration-wavy underline-offset-4">Rassegna stampa</a></li>
                    <li><a href="#" class="decoration-postit-rosa hover:text-postit-giallo underline decoration-wavy underline-offset-4">Informativa privacy</a></li>
                    <li><a href="#" class="decoration-postit-rosa hover:text-postit-giallo underline decoration-wavy underline-offset-4">Cookie policy</a></li>
                </ul>
            </div>
        </div>

        <div class="text-carta/70 border-carta/15 space-y-1 border-t px-5 py-5 text-center text-sm">
            <p>&copy; {{ now()->year }} Ivan Scarabocchio · Tutti gli scarabocchi riservati</p>
            <p lang="en">
                Made with <span role="img" aria-label="fingers">🈯</span> by
                <a href="https://fabioangelici.com" target="_blank" rel="noopener noreferrer"
                    class="decoration-postit-rosa hover:text-postit-giallo underline decoration-wavy underline-offset-4">Fabio
                    Angelici<span class="sr-only" lang="it"> (si apre in una nuova scheda)</span></a>
            </p>
        </div>
    </footer>
</body>

</html>
