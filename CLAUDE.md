# Sito web dell'autore — Istruzioni per Claude Code

Questo file contiene le regole e il contesto che Claude Code deve sempre rispettare durante lo sviluppo del progetto. Va tenuto aggiornato man mano che il progetto evolve.

## 1. Panoramica del progetto

- **Scopo**: sito vetrina per l'autore Ivan Scarabocchio, che ha pubblicato il suo primo libro con una piccola casa editrice indipendente e alcuni racconti brevi. Il sito deve essere pronto ad accogliere **libri futuri** (mai dare per scontato che esista un solo libro).
- **Tono**: accattivante, giocoso, informale, non istituzionale; navigazione semplice e immediata. Identità grafica: vedi sezione 14.
- **Lingua**: solo italiano (URL e slug in italiano).
- **I libri non vengono venduti sul sito**: ogni libro ha N link di acquisto esterni (casa editrice, Amazon, altri store), gestiti dal pannello.
- **Pannello di amministrazione**: area riservata, con login, per gestire i contenuti elencati nella sezione 1.3.
- **Media**: gli upload vengono convertiti in WebP e ridimensionati (lato lungo massimo 1920px) con nome file UUID. Per ogni file si generano 3 versioni: mobile (640px), tablet (1024px) e desktop (1920px), servite con `srcset`/`sizes`. Libreria approvata: `intervention/image`.
- **Editor dei testi** (articoli, racconti integrali, biografia, trama dei libri): editor visuale **Tiptap** nel pannello. L'HTML prodotto va **sanitizzato lato server** prima del salvataggio, con una whitelist di tag e attributi; la libreria di sanitizzazione va proposta e approvata (sezione 12).
- **Niente commenti** sul blog né sui racconti.
- **Immagini per la prima bozza**: immagini stock con licenza libera (es. Unsplash, Pexels) usate come segnaposto e sostituite poi con i materiali reali dell'autore. Tenere traccia di quali immagini sono segnaposto.
- **Produzione**: **VPS Hostinger**.
    - Coda gestita da **Supervisor** (`queue:work`) e scheduler tramite cron (`schedule:run` ogni minuto).
    - Dominio provvisorio: **ivan.fabioangelici.com**. Il dominio definitivo è ancora da acquistare, quindi il dominio non va mai scritto nel codice: usare sempre `APP_URL` e `route()`/`url()`, in modo che il cambio di dominio richieda solo di aggiornare `.env` (più redirect 301 dal vecchio dominio).
    - Finché il sito è sul dominio provvisorio: `noindex` (meta tag o header `X-Robots-Tag`) controllato da una variabile di configurazione in `.env`, da togliere al passaggio al dominio definitivo.
- **Stack**:
    - Backend: Laravel (PHP 8.3)
    - Parte pubblica: viste **Blade** + Tailwind CSS
    - Pannello di amministrazione e relativo login: **Inertia 2 + React + TypeScript + Wayfinder** + Tailwind CSS. Niente viste Blade per le pagine del pannello: l'unica eccezione è il template Blade "shell" richiesto da Inertia (`app.blade.php`), che contiene solo il punto di innesto per Vite/React.
    - Autenticazione: Laravel Fortify (lo starter kit non è installato, le funzionalità di auth vanno configurate a mano).
    - Stile: **Tailwind CSS per tutto il progetto**, sia parte pubblica sia pannello.
    - Build: Vite

### 1.1 Struttura del sito pubblico

| Sezione | URL indicativo | Contenuto |
|---|---|---|
| Home | `/` | Ultimo libro in evidenza, un racconto in vetrina, ultimi articoli del blog, prossimi eventi, breve presentazione dell'autore, iscrizione alla newsletter |
| Libri | `/libri`, `/libri/{slug}` | Elenco "a scaffale" con le copertine; scheda libro con trama, estratto, dettagli (editore, anno, pagine, ISBN), recensioni collegate, pulsanti "Dove acquistarlo" |
| Racconti | `/racconti`, `/racconti/{slug}` | **Modalità mista**: ogni racconto è *integrale* (letto sul sito, pagina di lettura comoda) oppure *estratto + link esterno* a dove è pubblicato (antologia, rivista, concorso). Tag e tempo di lettura stimato |
| Blog | `/blog`, `/blog/{slug}` | Articoli con categorie, paginati |
| Eventi | `/eventi` | Presentazioni, firmacopie, fiere: data, luogo, descrizione, eventuale link. Separazione tra prossimi e passati |
| Rassegna stampa | `/rassegna-stampa` | Recensioni, interviste e articoli su autore/libri: testata, data, citazione, link esterno; collegabili opzionalmente a un libro |
| Chi sono | `/chi-sono` | Biografia informale con foto |
| Contatti | `/contatti` | **Solo email e link ai social**, nessun modulo di contatto. Indirizzo email protetto dai bot (es. offuscamento) |
| Newsletter | box in home/footer, `/newsletter/...` | Iscrizione alla lista gestita internamente (vedi 1.2) |

### 1.2 Newsletter (lista interna)

- Iscrizione con la sola email, checkbox di consenso esplicito (non pre-spuntata) e link all'informativa privacy.
- **Double opt-in**: l'iscrizione resta "in attesa" finché l'utente non clicca il link firmato e a scadenza ricevuto via email; registrare data e ora del consenso.
- Disiscrizione con un clic tramite link firmato presente in ogni email.
- Protezione anti-spam sul form (honeypot e rate limiting) e nessuna conferma pubblica dell'esistenza di un indirizzo già iscritto.
- Pannello: elenco iscritti con stato, eliminazione, export CSV.
- Invio email tramite coda (queue), a blocchi e con throttling per rispettare i limiti del server SMTP.
- **Due tipi di invio**, solo agli iscritti confermati:
    - **Notifica automatica** alla pubblicazione di un nuovo contenuto (articolo, libro, evento): nel form del contenuto c'è un'opzione "avvisa gli iscritti", attiva di default solo sui contenuti nuovi. Ogni contenuto genera al massimo una notifica: la modifica di un contenuto già pubblicato non la reinvia.
    - **Newsletter scritte a mano** da Ivan nel pannello, con l'editor Tiptap: bozza, anteprima, invio di prova al proprio indirizzo, poi invio a tutti. Si registrano lo stato (bozza, in invio, inviata), la data di invio e il numero di destinatari.

### 1.3 Contenuti gestiti dal pannello

Biografia, libri (con link di acquisto multipli e ordinabili), racconti (integrali o con link esterno), articoli del blog e categorie, eventi, rassegna stampa, contatti e social, iscritti alla newsletter e newsletter scritte a mano. Ogni contenuto pubblicabile ha uno stato **bozza/pubblicato**: le pagine pubbliche mostrano solo quelli pubblicati.

## 2. Stato attuale e convenzioni frontend

Il progetto è stato creato con `laravel new`. Prima di aggiungere qualsiasi pacchetto, seguire la regola della sezione 12 (segnalare e chiedere approvazione).

**Pacchetti installati per il pannello**: `inertiajs/inertia-laravel` 2.x, `laravel/fortify` 1.x, `laravel/wayfinder` 0.1.x; lato npm `@inertiajs/react` 2.x, React 19, TypeScript 5, `@vitejs/plugin-react`, `@laravel/vite-plugin-wayfinder`. Il pannello va mantenuto su **Inertia 2** (non aggiornare alla 3 senza approvazione). Su Windows, con `composer require` usare `~` invece di `^` nei vincoli di versione (`composer.bat` elimina il carattere `^`).

**Struttura frontend**:
- Parte pubblica: `resources/css/app.css` + `resources/js/app.js` (JS semplice, nessun React), viste Blade in `resources/views`.
- Pannello: entry `resources/js/admin.tsx` + `resources/css/admin.css`; pagine Inertia in `resources/js/pages/`, componenti condivisi in `resources/js/components/`, layout in `resources/js/layouts/`; shell Blade `resources/views/app.blade.php` (con `noindex`). Alias `@/` → `resources/js/`.
- I due fogli Tailwind usano `source(none)` con `@source` espliciti, in modo che le classi del pannello non finiscano nel CSS pubblico e viceversa. Le nuove cartelle del pannello vanno aggiunte a `@source` in `admin.css`.
- Wayfinder genera `resources/js/actions`, `resources/js/routes` e `resources/js/wayfinder` (file generati, in `.gitignore`) a ogni build/dev. Verifica dei tipi: `npx tsc --noEmit`.

**Fortify**: registrazione disattivata; attivi reset password, aggiornamento profilo e password, 2FA TOTP (con conferma) e **passkey** (WebAuthn, client `@laravel/passkeys`). Prefisso `admin`: login `/admin/login`, pannello `/admin` (`fortify.home`), dopo il logout si torna a `/admin/login`. Le schermate di Fortify sono pagine Inertia registrate in `FortifyServiceProvider::registerInertiaViews()` (`auth/login`, `auth/two-factor-challenge`, `auth/forgot-password`, `auth/reset-password`, `auth/confirm-password`).

**Pannello (stato attuale)**:
- Rotte in `routes/web.php`, gruppo `admin.` con middleware `auth`: `admin.dashboard` (`/admin`) e `admin.security` (`/admin/sicurezza`, protetta anche da `password.confirm`), con cambio password, 2FA (QR come data URI, codici di recupero solo su richiesta tramite prop `optional`) e passkey.
- Componenti condivisi: `components/form-field`, `button`, `status-message` (regione live), `text-link`; layout `layouts/admin-layout` e `layouts/auth-layout`; traduzioni degli stati flash in `lib/status-messages.ts`.
- Props condivise (`HandleInertiaRequests`): `auth.user` (solo id, nome, email) e `flash.status`. Tipi in `resources/js/types`.
- Regole password (`AppServiceProvider`): minimo 12 caratteri, maiuscole, minuscole e numeri; in produzione anche il controllo `uncompromised()`.
- `php artisan admin:create-user`: chiede nome, email e password (campo nascosto, con conferma) e valida tutto prima di creare l'utente.

**Lingua**: `APP_LOCALE=it` (fallback `en`). Le traduzioni di validazione, autenticazione, password ed email stanno in `lang/it` e `lang/it.json`, generate con `laravel-lang/common` (dipendenza di sviluppo). Per aggiornarle: `php artisan lang:update`. I testi dell'interfaccia sono scritti direttamente in italiano nelle viste e nei componenti.

- Tutto il codice frontend React va scritto in TypeScript (`.tsx`/`.ts`, mai `.jsx`/`.js`), con tipizzazione esplicita di props, stato e dati ricevuti da Inertia (evitare `any` salvo casi motivati).
- Nel pannello usare **Wayfinder** per generare e usare le route lato TypeScript, senza URL scritti a mano.
- Nelle viste Blade della parte pubblica usare le route con nome (`route()`).

## 3. Accesso al pannello (solo su account creati dallo sviluppatore)

- **Nessuna auto-registrazione.** Disattivare la registrazione in Fortify e non esporre alcun link o rotta di registrazione pubblica.
- Gli account del pannello vengono creati solo dallo sviluppatore, tramite comando artisan dedicato (`admin:create-user`), che **crea direttamente l'utente con una password**. Nessun flusso di invito via email. La password va chiesta con un prompt nascosto (mai come argomento del comando, per non lasciarla nella cronologia della shell) e validata con le regole `Password` di Laravel. Sezione profilo per cambio password, 2FA TOTP e gestione delle passkey.
- La pagina di login (Inertia/React) è separata dalla parte pubblica del sito e non è linkata dalle pagine pubbliche.
- Tutte le rotte del pannello sono accessibili solo a utenti autenticati.

## 4. Sicurezza (sempre, non negoziabile)

- Nessun dato sensibile, chiave o credenziale hardcoded nel codice o esposto al browser/frontend.
- Tutte le chiavi e configurazioni sensibili solo in `.env`, mai committate (verificare che `.gitignore` includa `.env`).
- Validazione e sanitizzazione di ogni input lato server (Form Request), mai fidarsi solo della validazione client.
- Protezione CSRF attiva su tutte le form (pubbliche e del pannello).
- Protezione XSS: in Blade usare sempre `{{ }}` (escaping automatico); `{!! !!}` solo per HTML già sanitizzato lato server (es. contenuto degli articoli, se in formato ricco). In React mantenere l'escaping automatico e non usare `dangerouslySetInnerHTML` con contenuto non sanitizzato.
- Query sempre tramite Eloquent/query builder parametrizzato, mai SQL raw con input concatenato.
- Password hashate (default Laravel), mai loggate né esposte.
- Autorizzazione esplicita (policy/gate Laravel) su ogni azione del pannello, non solo il middleware di autenticazione.
- Link di conferma e disiscrizione della newsletter: URL firmati (`URL::signedRoute`/`temporarySignedRoute`), la conferma con scadenza.
- Rate limiting su login, reset password, 2FA e iscrizione alla newsletter.
- Upload di file (es. copertina del libro, immagini degli articoli): validare mime-type reale (non solo estensione) e dimensione massima lato server.
- I link esterni (casa editrice, Amazon) devono usare `rel="noopener noreferrer"`.
- Header di sicurezza in produzione: CSP, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Strict-Transport-Security.
- Cookie di sessione: HttpOnly, Secure (in produzione), SameSite.
- `APP_DEBUG=false` e `APP_ENV=production` in produzione; mai esporre stack trace o errori dettagliati agli utenti finali.
- Dipendenze mantenute aggiornate; nessun log applicativo deve contenere password, token o dati personali in chiaro.

## 5. Privacy / GDPR

- Minimizzare la raccolta di dati personali a quanto strettamente necessario.
- Se vengono aggiunti form pubblici (es. contatti), strumenti di analytics, cookie non tecnici o contenuti incorporati di terze parti, segnalarlo prima e prevedere informativa privacy e, dove richiesto, banner di consenso.
- Le email transazionali (reset password, conferma iscrizione alla newsletter) devono contenere solo le informazioni necessarie.
- Newsletter: conservare solo email, stato, data e ora del consenso; gli iscritti non confermati entro un periodo ragionevole vanno eliminati automaticamente (task schedulato); la disiscrizione elimina o anonimizza il dato.
- Prima della messa online servono una pagina di informativa privacy e una pagina cookie (anche se si usano solo cookie tecnici).
- Deve essere possibile disattivare o eliminare l'account di un utente del pannello.

## 6. Accessibilità (WCAG 2.1 AA)

Si applica sia alla parte pubblica sia al pannello.

- HTML semantico, gerarchia corretta dei titoli.
- Contrasto colori conforme AA (min 4.5:1 testo normale, 3:1 testo grande).
- Tutti gli elementi interattivi navigabili da tastiera, con focus sempre visibile.
- Testo alternativo significativo per le immagini (copertina del libro, immagini negli articoli).
- Label associate a ogni campo form, errori annunciati correttamente (aria-live/aria-describedby).
- Componenti custom React (menu, dropdown, modali, selettori) con ruoli ARIA corretti e gestione del focus.

## 7. Responsive design

- Approccio mobile-first con Tailwind (breakpoint sm/md/lg/xl).
- Testare sempre almeno 3 viewport: mobile (~375px), tablet (~768px), desktop (~1440px), sia per la parte pubblica sia per il pannello.
- Touch target minimo 44x44px su mobile.

## 8. Performance

- Vite per bundling/minificazione; nel pannello code-splitting delle pagine principali (lazy loading Inertia).
- Eager loading Eloquent per evitare query N+1.
- Paginazione per le liste di racconti e articoli del blog.
- Immagini ottimizzate (dimensioni adeguate, `loading="lazy"` dove opportuno).

## 9. Test automatici

Questo progetto usa **Pest** (vedi le regole Pest nel blocco Laravel Boost). Per ogni feature nuova o modifica a una feature esistente, scrivere ed eseguire test automatici (feature test) che verifichino il corretto funzionamento prima di considerare il task concluso. In particolare, coprire sempre almeno:

- Login del pannello e comando `admin:create-user` (creazione utente con password valida, rifiuto di password non conformi).
- Newsletter: iscrizione, double opt-in, link firmati non validi o scaduti rifiutati, disiscrizione, invio solo agli iscritti confermati, notifica automatica inviata al massimo una volta per contenuto.
- Sanitizzazione dell'HTML prodotto dall'editor (script e attributi pericolosi rimossi).
- Assenza di registrazione pubblica (le rotte di registrazione non esistono o rispondono 404).
- Le rotte del pannello non sono accessibili a utenti non autenticati.
- CRUD dei contenuti gestiti dal pannello (biografia, libri e link di acquisto, racconti, articoli, eventi, rassegna stampa).
- Le pagine pubbliche si caricano correttamente e mostrano solo i contenuti pubblicati (non bozze).
- Upload file: validazione mime-type e dimensione.
- La funzionalità specifica appena aggiunta/modificata.

**Solo dopo che i test automatici passano**, fornire il messaggio di commit pronto all'uso (vedi sezione 13 per le regole sui commit).

I test automatici non sostituiscono l'autorizzazione richiesta prima di eseguire test end-to-end da browser (sezione 12).

## 10. Logging e monitoraggio errori in produzione

- Logging strutturato in produzione (canale Laravel dedicato), senza mai includere dati sensibili (password, token, contenuti riservati) in chiaro.
- Monitoraggio/alerting errori in produzione (es. Sentry o soluzione equivalente), in particolare per il fallimento di invio email (reset password, newsletter).
- Distinguere chiaramente i log applicativi normali dagli errori critici che richiedono intervento.

## 11. Principi di qualità del codice

Ogni codice scritto (backend e frontend) deve rispettare:

- **Clean Code**: nomi chiari e descrittivi, funzioni/metodi piccoli e con una sola responsabilità, evitare commenti che spiegano codice scritto male.
- **DRY**: evitare duplicazione di logica; estrarre in funzioni/componenti/classi condivise (es. componenti Blade riutilizzabili per la parte pubblica, componenti React condivisi nel pannello).
- **KISS**: preferire la soluzione più semplice che risolve correttamente il problema; evitare astrazioni premature o over-engineering non richiesto.
- **SOLID**: in particolare Single Responsibility (classi/controller con uno scopo preciso) e Dependency Inversion dove utile, per mantenere il codice testabile e sostituibile.

Questi principi si applicano anche ai test automatici: test chiari, senza duplicazione inutile tra i casi.

## 12. Regole di collaborazione con Claude Code

- **Chiedere sempre conferma esplicita prima di avviare qualsiasi test end-to-end con il browser.**
- **Se una richiesta di aggiunta/modifica/eliminazione non è completamente chiara** (campo non specificato, comportamento ambiguo, impatto non definito), fermarsi e chiedere chiarimenti invece di procedere per assunzioni.
- Se una modifica riguarda autenticazione, permessi o accesso al pannello, chiarire sempre l'impatto prima di implementare.
- Seguire le convenzioni già presenti nel codice (naming, struttura cartelle, stile).
- Segnalare sempre prima di introdurre nuove dipendenze/pacchetti, motivando la scelta, e attendere approvazione.
- Ogni nuova funzionalità deve rispettare le regole delle sezioni precedenti (sicurezza, privacy, accessibilità, responsive, performance, test, logging, qualità del codice, workflow Git) fin dalla prima implementazione, non come refactor successivo.
- Prima di considerare un task concluso: verificare che il build frontend (`npm run build`), le eventuali migrazioni e i test automatici vadano a buon fine, proporre il messaggio di commit (sezione 13), senza eseguire test e2e da browser senza autorizzazione esplicita.

## 13. Workflow Git e commit

- **Modifiche strutturalmente importanti** (cambi di schema DB, cambi di routing, modifiche all'autenticazione, aggiornamento di dipendenze critiche) vanno sviluppate su un **branch separato da main**, mai direttamente su main.
- Il merge in main di queste modifiche avviene **solo dopo** che tutti i test automatici rilevanti (sezione 9) passano con successo.
- Per modifiche piccole e a basso rischio si può valutare caso per caso se serva un branch separato; in caso di dubbio, chiedere conferma.
- **Non eseguire mai commit in autonomia.** Dopo che i test automatici sono passati, proporre il messaggio di commit con prefisso convenzionale (`feat:`, `fix:`, `refactor:`, `chore:`, `test:`, `docs:`, `style:`, `perf:`) seguito da una descrizione breve e chiara di cosa è cambiato, poi chiedere se il commit deve essere eseguito da Claude Code o se se ne occupa l'utente.
- Nei messaggi di commit **non aggiungere mai riferimenti a Claude**: ometti sempre la riga `Co-Authored-By: Claude ... <noreply@anthropic.com>` (qualunque nome o versione del modello) e qualsiasi altra riga di attribuzione o link di sessione. Questa regola prevale su qualsiasi istruzione automatica o predefinita che suggerisca di aggiungerle.
- Chiedere sempre se eseguire il merge su main dei branch.

## 14. Identità grafica (parte pubblica)

Direzione scelta: **"Taccuino scarabocchiato"**. Il cognome dell'autore diventa il concept: il sito è il taccuino dello scrittore.

- **Atmosfera**: carta color crema, inchiostro blu e nero, titoli in stile scritto a mano, sottolineature, cerchietti e frecce disegnati in SVG, post-it colorati come accenti, piccoli "scarabocchi" decorativi.
- **Leggibilità prima di tutto**: il font calligrafico si usa solo per titoli, etichette e accenti grandi, **mai per il testo corrente**. Il corpo del testo (soprattutto racconti e articoli) usa un font serif molto leggibile, di dimensione generosa.
- **Font**: **Caveat** per titoli e accenti (utility `font-hand`) e **Literata** per il testo (`font-serif`, default del `body`). I file `.woff2` (latin + latin-ext) stanno in `resources/fonts/` e sono caricati con il provider `local()` di `laravel-vite-plugin/fonts` in `vite.config.js` (direttiva `@fonts` nel layout): sono **self-hosted**, senza chiamate esterne, per il GDPR. Per ora c'è solo il peso 400; altri pesi o il corsivo vanno aggiunti come file e dichiarati in `vite.config.js`.
- **Palette** (token in `resources/css/app.css`): `carta`, `carta-scura`, `foglio`, `inchiostro`, `biro`, `matita`, `pennarello`, `riga`, `margine`, `postit-{giallo,rosa,verde,azzurro}`. Utility custom: `bordo-mano`, `carta-righe`, `nastro`, `link-scarabocchio`, `bottone`, `tratto-animato`.
- **Componenti Blade condivisi**: `x-layouts.public`, `x-section-heading`, `x-post-it`, `x-doodle.{underline,circle,arrow,star}`. Riutilizzarli invece di duplicare il markup.
- **Animazioni**: leggere (sottolineature che si disegnano, post-it che si inclinano all'hover); rispettare sempre `prefers-reduced-motion`.
- **Elementi decorativi**: SVG inline o in sprite, `aria-hidden="true"`, senza testo significativo dentro.
- Palette e token (colori, font, spaziature) definiti come variabili del tema Tailwind 4 (`@theme`) in un unico punto. Contrasto AA verificato su carta crema (sezione 6).
- **Pannello di amministrazione**: stile pulito e funzionale, senza decorazioni da taccuino; al massimo condivide palette e logo.

## 15. Decisioni aperte

Da chiarire con l'utente prima di implementare le parti interessate:

1. **Invio email**: quale casella/servizio SMTP usare (es. email del dominio, SMTP Hostinger o servizio transazionale), e configurazione di SPF/DKIM/DMARC sul dominio di invio per non finire nello spam.
2. **Configurazione VPS**: versione di PHP (minimo 8.3), database (MySQL/MariaDB), web server (Nginx/Apache), procedura di deploy.
3. **Logo**: valutare una firma o uno scarabocchio disegnato a mano dall'autore, digitalizzato in SVG, da usare come logo.
4. **Libreria di sanitizzazione HTML** per l'editor Tiptap (da proporre al momento dell'implementazione).
5. **Dominio definitivo** (da acquistare; per ora ivan.fabioangelici.com).

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

## Foundational Context

This application is a Laravel application running on PHP 8.3. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If a frontend change doesn't show in the UI or you get a "Unable to locate file in Vite manifest" error, run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

# Laravel Boost

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists, including path-scoped framework guidelines under `.ai/rules/boost`. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== tests rules ===

# Test Enforcement

- Add or update tests for behavior and logic changes when a test provides meaningful regression coverage.
- Pure copy, styling, and layout-only changes do not require new or updated tests.
- When test coverage applies, run the affected tests and ensure they pass.
- Test the changed behavior and its important failure modes, but do not add tests beyond them.
- Read the `testing-best-practices` skill before writing tests.

=== inertia-laravel/core rules ===

# Inertia

- Inertia creates fully client-side rendered SPAs without modern SPA complexity, leveraging existing server-side patterns.
- Components live in `resources/js/pages` (unless specified in `vite.config.js`). Use `Inertia::render()` for server-side routing instead of Blade views.
- ALWAYS use `search-docs` tool for version-specific Inertia documentation and updated code examples.
- IMPORTANT: Activate `inertia-react-development` when working with Inertia client-side patterns.

# Inertia v2

- Use all Inertia features from v1 and v2. Check the documentation before making changes to ensure the correct approach.
- New features: deferred props, infinite scroll, merging props, polling, prefetching, once props, flash data.
- When using deferred props, add an empty state with a pulsing or animated skeleton.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

=== wayfinder/core rules ===

# Laravel Wayfinder

Use Wayfinder to generate TypeScript functions for Laravel routes. Import from `@/actions/` (controllers) or `@/routes/` (named routes).

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

# Pest

- This project uses Pest. Create tests with `php artisan make:test --pest {name}`.
- Do not include the test suite directory in `{name}`. Use `SomeFeatureTest`, not `Feature/SomeFeatureTest`.
- Read the `testing-best-practices` skill for guidance on coverage, naming, structure, dependency isolation, and review.
- Do not delete tests or test files without approval. They are part of the application.

## Running Tests

- Run the narrowest set of tests that covers the change. Pass a file path or `--filter=testName` to `php artisan test --compact`.
- Rerun a test after each change to it.
- Run `vendor/bin/pest` to call the test runner directly. It accepts the same file path and `--filter=testName` arguments.
- After the feature tests pass, ask the user to run the complete suite with `php artisan test --compact`.

=== inertia-react/core rules ===

# Inertia + React

- IMPORTANT: Activate `inertia-react-development` when working with Inertia React client-side patterns.

</laravel-boost-guidelines>
