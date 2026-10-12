{{-- Anteprima statica: tutti i contenuti e le immagini sono segnaposto, da sostituire con i dati gestiti dal pannello. --}}
<x-layouts.public>
    {{-- Benvenuto --}}
    <section aria-labelledby="titolo-benvenuto" class="relative">
        <div class="mx-auto grid max-w-6xl items-center gap-14 px-5 pt-8 pb-20 md:grid-cols-[1.25fr_1fr] md:pt-14 md:pb-28">
            <div>
                <p class="font-hand text-pennarello -rotate-2 text-2xl md:text-3xl">Ciao! Benvenuto nel mio taccuino</p>

                <h1 id="titolo-benvenuto" class="font-hand mt-4 text-6xl leading-[0.95] sm:text-7xl lg:text-8xl">
                    Sono Ivan,
                    <span class="relative mr-5 inline-block whitespace-nowrap pr-1">
                        scrivo storie
                        <x-doodle.circle animated
                            class="text-pennarello pointer-events-none absolute -top-3 -left-5 h-[calc(100%+1.5rem)] w-[calc(100%+3rem)]" />
                    </span>
                    e ogni tanto scarabocchio.
                </h1>

                <p class="text-matita mt-8 max-w-prose text-lg leading-relaxed">
                    Qui trovi i miei libri, i racconti, gli appunti del diario e le date in cui possiamo
                    incontrarci di persona. Mettiti comodo, il caffè è virtuale ma la compagnia è vera.
                </p>

                <div class="mt-9 flex flex-wrap gap-4">
                    <a href="#libro" class="bottone bg-postit-giallo">Scopri il libro</a>
                    <a href="#racconti" class="bottone bg-foglio">Leggi un racconto</a>
                </div>
            </div>

            <div class="relative mx-auto w-full max-w-sm">
                <figure class="nastro bg-foglio rotate-3 p-3 pb-4 shadow-[4px_14px_24px_-10px_rgb(29_36_64/0.45)]">
                    <img src="{{ asset('images/segnaposto/autore.webp') }}" alt="Ritratto di Ivan Scarabocchio"
                        width="900" height="1100" class="aspect-9/11 w-full object-cover grayscale-30"
                        fetchpriority="high">
                    <figcaption class="font-hand mt-3 text-center text-2xl">io, con la faccia da scrittore</figcaption>
                </figure>

                <x-post-it color="rosa"
                    class="font-hand absolute -top-8 -left-2 w-40 -rotate-6 text-center text-2xl leading-tight sm:-left-16">
                    È uscito il mio primo libro!
                </x-post-it>
                <a href="#libro" class="font-hand hover:text-biro absolute -bottom-20 -left-2 hidden items-end gap-1 text-2xl sm:flex">
                    <span class="-rotate-3">eccolo qui sotto</span>
                    <x-doodle.arrow class="h-14 w-16 translate-y-6" />
                </a>
            </div>
        </div>
    </section>

    {{-- Il libro --}}
    <section id="libro" aria-labelledby="titolo-libro" class="bg-carta-scura relative">
        <svg viewBox="0 0 1440 40" preserveAspectRatio="none" aria-hidden="true" focusable="false"
            class="text-carta-scura absolute -top-9.75 left-0 h-10 w-full">
            <path d="M0 40 C 240 0, 480 30, 720 14 S 1200 4, 1440 26 L 1440 40 Z" fill="currentColor" />
        </svg>

        <div class="mx-auto grid max-w-6xl items-center gap-14 px-5 py-20 md:grid-cols-[1fr_1.3fr] md:py-24">
            {{-- Copertina segnaposto --}}
            <div class="mx-auto w-full max-w-xs -rotate-2 md:max-w-60 lg:max-w-xs transition-transform duration-300 hover:rotate-0">
                <div
                    class="relative aspect-2/3 overflow-hidden rounded-r-md shadow-[10px_18px_30px_-12px_rgb(29_36_64/0.6)]">
                    <img src="{{ asset('images/segnaposto/copertina-sfondo.webp') }}"
                        alt="Copertina del libro “Le cose che restano a galla”" width="700" height="1000"
                        class="absolute inset-0 h-full w-full object-cover" loading="lazy">
                    <div aria-hidden="true"
                        class="absolute inset-0 flex flex-col justify-between bg-linear-to-b from-black/40 via-transparent to-black/60 p-6 text-white">
                        <p class="text-sm tracking-[0.3em] uppercase">Ivan Scarabocchio</p>
                        <div>
                            <p class="font-hand text-5xl leading-none">Le cose che restano a galla</p>
                            <p class="mt-3 text-xs tracking-widest uppercase">Casa Editrice</p>
                        </div>
                    </div>
                    <div aria-hidden="true" class="absolute inset-y-0 left-0 w-3 bg-linear-to-r from-black/35 to-transparent">
                    </div>
                </div>
            </div>

            <div>
                <p class="font-hand text-pennarello -rotate-1 text-2xl">Fresco di stampa</p>
                <h2 id="titolo-libro" class="font-hand mt-2 text-5xl leading-none md:text-6xl">
                    Le cose che restano a galla
                </h2>

                <p class="mt-6 text-lg leading-relaxed">
                    Un paese di mare, un'estate che non vuole finire e una manciata di personaggi che hanno
                    tutti qualcosa da nascondere. Un romanzo che fa sorridere e poi, quando meno te lo aspetti,
                    ti lascia lì a pensarci su.
                </p>

                <dl class="text-matita mt-6 grid grid-cols-3 gap-4 border-y-2 border-dashed border-inchiostro/25 py-4 text-sm">
                    <div>
                        <dt class="font-hand text-inchiostro text-xl">Editore</dt>
                        <dd>Casa Editrice</dd>
                    </div>
                    <div>
                        <dt class="font-hand text-inchiostro text-xl">Anno</dt>
                        <dd>2026</dd>
                    </div>
                    <div>
                        <dt class="font-hand text-inchiostro text-xl">Pagine</dt>
                        <dd>224</dd>
                    </div>
                </dl>

                <h3 class="font-hand mt-8 text-3xl">Dove trovarlo</h3>
                <ul class="mt-4 flex flex-wrap gap-4">
                    <li>
                        <a href="#" class="bottone bg-postit-giallo" target="_blank" rel="noopener noreferrer">
                            Casa editrice <span class="sr-only">(si apre in una nuova scheda)</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="bottone bg-foglio" target="_blank" rel="noopener noreferrer">
                            Amazon <span class="sr-only">(si apre in una nuova scheda)</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="bottone bg-foglio" target="_blank" rel="noopener noreferrer">
                            IBS <span class="sr-only">(si apre in una nuova scheda)</span>
                        </a>
                    </li>
                </ul>
                <p class="text-matita mt-4 text-sm">…oppure chiedilo al tuo libraio di fiducia: gli farai un regalo.</p>

                <a href="#" class="link-scarabocchio font-hand mt-6 inline-block text-2xl">Leggi la scheda completa &rarr;</a>
            </div>
        </div>
    </section>

    {{-- Racconti --}}
    <section id="racconti" aria-labelledby="titolo-racconti" class="mx-auto max-w-6xl px-5 pt-20 pb-10 md:pt-28 md:pb-12">
        <x-section-heading id="titolo-racconti" title="Racconti" kicker="Storie brevi, da leggere col caffè"
            link="#" link-label="Tutti i racconti" />

        <div class="grid gap-10 lg:grid-cols-[1.6fr_1fr]">
            <article class="carta-righe bordo-mano border-inchiostro border-2 p-6 pl-14 shadow-[6px_6px_0_var(--color-inchiostro)] md:p-10 md:pl-16">
                <div class="grid items-start gap-8 md:grid-cols-[13rem_1fr]">
                    <div class="nastro bg-foglio rotate-2 p-2 shadow-md">
                        <img src="{{ asset('images/segnaposto/racconto.webp') }}" alt="" width="1200" height="800"
                            class="aspect-square w-full object-cover" loading="lazy">
                    </div>
                    <div>
                        <p class="font-hand text-pennarello text-xl">In vetrina · lettura di 8 minuti</p>
                        <h3 class="font-hand mt-1 text-4xl leading-none">L'uomo che collezionava temporali</h3>
                        <p class="mt-4 leading-8 italic">
                            «Ogni volta che il cielo si faceva scuro, il signor Benassi usciva col suo barattolo di vetro.
                            In paese ridevano, ma nessuno aveva mai avuto il coraggio di chiedergli cosa ci fosse
                            dentro…»
                        </p>
                        <ul class="mt-4 flex flex-wrap gap-2 text-sm" aria-label="Argomenti">
                            <li class="bg-postit-azzurro px-2 py-0.5">surreale</li>
                            <li class="bg-postit-verde px-2 py-0.5">paese</li>
                        </ul>
                        <a href="#" class="link-scarabocchio font-hand mt-5 inline-block text-2xl">Leggi il racconto &rarr;</a>
                    </div>
                </div>
            </article>

            <div>
                <h3 class="font-hand text-3xl">Altri racconti</h3>
                <ul class="mt-4 divide-y-2 divide-dashed divide-inchiostro/20">
                    <li class="py-4">
                        <a href="#" class="group block">
                            <span class="font-hand group-hover:text-biro text-2xl">Il cane che sapeva l'inglese</span>
                            <span class="text-matita block text-sm">Integrale sul sito · 5 minuti</span>
                        </a>
                    </li>
                    <li class="py-4">
                        <a href="#" class="group block" target="_blank" rel="noopener noreferrer">
                            <span class="font-hand group-hover:text-biro text-2xl">Ultima corsa per Riccione
                                <span aria-hidden="true">↗</span></span>
                            <span class="text-matita block text-sm">Pubblicato nell'antologia “Estati” · estratto
                                <span class="sr-only">(sito esterno, si apre in una nuova scheda)</span></span>
                        </a>
                    </li>
                    <li class="py-4">
                        <a href="#" class="group block">
                            <span class="font-hand group-hover:text-biro text-2xl">Lettera a un frigorifero</span>
                            <span class="text-matita block text-sm">Integrale sul sito · 3 minuti</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    {{-- Diario (blog) --}}
    <section id="diario" aria-labelledby="titolo-diario" class="mx-auto max-w-6xl px-5 py-10 md:py-16">
        <x-section-heading id="titolo-diario" title="Dal diario" kicker="Appunti, novità e pensieri sparsi" link="#"
            link-label="Tutti gli articoli" />

        @php
            $articoli = [
                ['immagine' => 'blog-macchina-scrivere', 'categoria' => 'Scrittura', 'data' => '2026-10-02', 'titolo' => 'Come è nato il libro (e quante volte l\'ho buttato via)', 'estratto' => 'Tre anni, due computer, un quaderno caduto in mare. Il dietro le quinte del mio primo romanzo.', 'rotazione' => '-rotate-1'],
                ['immagine' => 'blog-scrivania', 'categoria' => 'Novità', 'data' => '2026-09-18', 'titolo' => 'La prima presentazione: com\'è andata', 'estratto' => 'Avevo preparato un discorso. Poi è entrato un bambino con un gelato e ho improvvisato.', 'rotazione' => 'rotate-1'],
                ['immagine' => 'blog-caffe', 'categoria' => 'Letture', 'data' => '2026-09-01', 'titolo' => 'Cinque libri che mi hanno fatto venire voglia di scrivere', 'estratto' => 'Una lista breve, molto personale e assolutamente non oggettiva.', 'rotazione' => '-rotate-2'],
            ];
        @endphp

        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($articoli as $articolo)
                <article
                    class="nastro bg-foglio {{ $articolo['rotazione'] }} group flex flex-col p-4 pb-6 shadow-[3px_10px_20px_-10px_rgb(29_36_64/0.45)] transition-transform hover:rotate-0">
                    <img src="{{ asset('images/segnaposto/' . $articolo['immagine'] . '.webp') }}" alt=""
                        width="900" height="600" class="aspect-3/2 w-full object-cover" loading="lazy">
                    <p class="text-matita mt-4 flex items-center gap-2 text-sm">
                        <span class="bg-postit-giallo font-hand text-inchiostro px-2 text-lg">{{ $articolo['categoria'] }}</span>
                        <time datetime="{{ $articolo['data'] }}">
                            {{ \Illuminate\Support\Carbon::parse($articolo['data'])->locale('it')->isoFormat('D MMMM YYYY') }}
                        </time>
                    </p>
                    <h3 class="font-hand mt-2 text-3xl leading-none">
                        <a href="#" class="group-hover:text-biro">{{ $articolo['titolo'] }}</a>
                    </h3>
                    <p class="text-matita mt-3 leading-relaxed">{{ $articolo['estratto'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Eventi e rassegna stampa --}}
    <div class="mx-auto grid max-w-6xl gap-16 px-5 py-20 md:py-28 lg:grid-cols-2">
        <section id="eventi" aria-labelledby="titolo-eventi">
            <x-section-heading id="titolo-eventi" title="Ci vediamo?" kicker="Prossimi eventi" link="#"
                link-label="Tutti gli eventi" />

            @php
                $eventi = [
                    ['data' => '2026-10-24', 'titolo' => 'Presentazione in libreria', 'luogo' => 'Libreria del Porto, Ancona · ore 18:30'],
                    ['data' => '2026-11-08', 'titolo' => 'Firmacopie al festival', 'luogo' => 'Festival della lettura, Bologna · ore 16:00'],
                    ['data' => '2026-11-29', 'titolo' => 'Chiacchierata con i lettori', 'luogo' => 'Biblioteca comunale, Pesaro · ore 21:00'],
                ];
            @endphp

            <ul class="space-y-6">
                @foreach ($eventi as $evento)
                    @php($dataEvento = \Illuminate\Support\Carbon::parse($evento['data'])->locale('it'))
                    <li class="flex items-center gap-5">
                        <time datetime="{{ $evento['data'] }}" class="relative grid w-20 shrink-0 place-items-center py-2 text-center">
                            <span class="font-hand block text-5xl leading-none">{{ $dataEvento->format('j') }}</span>
                            <span class="text-pennarello block text-sm uppercase tracking-wider">{{ $dataEvento->isoFormat('MMM') }}</span>
                            <x-doodle.circle class="text-pennarello/70 pointer-events-none absolute inset-0 h-full w-full" />
                        </time>
                        <div>
                            <p class="font-hand text-3xl leading-none">{{ $evento['titolo'] }}</p>
                            <p class="text-matita mt-1">{{ $evento['luogo'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </section>

        <section id="rassegna-stampa" aria-labelledby="titolo-rassegna">
            <x-section-heading id="titolo-rassegna" title="Dicono del libro" kicker="Rassegna stampa" link="#"
                link-label="Tutta la rassegna" />

            <div class="grid gap-8 sm:grid-cols-2">
                <x-post-it color="giallo" class="-rotate-2">
                    <blockquote>
                        <p class="font-hand text-2xl leading-tight">“Un esordio fresco, ironico e pieno di cuore.”</p>
                    </blockquote>
                    <p class="text-matita mt-3 text-sm">— Il Giornale dei Libri</p>
                </x-post-it>
                <x-post-it color="verde" class="rotate-2 sm:mt-8">
                    <blockquote>
                        <p class="font-hand text-2xl leading-tight">“L'ho finito in una notte. Poi l'ho riletto.”</p>
                    </blockquote>
                    <p class="text-matita mt-3 text-sm">— Blog Pagine Sparse</p>
                </x-post-it>
                <x-post-it color="azzurro" class="rotate-1 sm:col-span-2 sm:mx-auto sm:w-2/3">
                    <blockquote>
                        <p class="font-hand text-2xl leading-tight">“Scarabocchio di nome, ma la penna è precisissima.”</p>
                    </blockquote>
                    <p class="text-matita mt-3 text-sm">— Radio Lettura</p>
                </x-post-it>
            </div>
        </section>
    </div>

    {{-- Newsletter (solo grafica: il form non è ancora collegato) --}}
    <section aria-labelledby="titolo-newsletter" class="mx-auto max-w-3xl px-5">
        <div class="carta-righe bordo-mano border-inchiostro relative border-2 p-8 pl-14 shadow-[6px_6px_0_var(--color-inchiostro)] md:p-12 md:pl-16">
            <x-doodle.star class="text-pennarello absolute -top-5 -right-3 h-12 w-12 rotate-12" />

            <h2 id="titolo-newsletter" class="font-hand text-5xl leading-none">Ti scrivo io</h2>
            <p class="mt-4 leading-8">
                Lasciami la tua email: ti avviso quando esce qualcosa di nuovo e, ogni tanto, ti mando una
                lettera dal mio taccuino. Niente spam, promesso.
            </p>

            <form action="#" method="post" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="newsletter-email" class="font-hand block text-2xl">La tua email</label>
                    <div class="mt-1 flex flex-col gap-3 sm:flex-row">
                        <input id="newsletter-email" name="email" type="email" required autocomplete="email"
                            class="bordo-mano border-inchiostro bg-foglio min-h-11 w-full border-2 px-4 py-2"
                            placeholder="nome@esempio.it">
                        <button type="submit" class="bottone bg-postit-giallo shrink-0">Iscrivimi</button>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <input id="newsletter-consenso" name="consent" type="checkbox" required
                        class="accent-biro mt-1.5 size-5 shrink-0">
                    <label for="newsletter-consenso" class="text-matita text-sm leading-6">
                        Ho letto l'<a href="#" class="link-scarabocchio">informativa privacy</a> e voglio ricevere la
                        newsletter. Puoi disiscriverti quando vuoi con un clic.
                    </label>
                </div>
            </form>
        </div>
    </section>
</x-layouts.public>
