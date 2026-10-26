@extends('layouts.app')

@section('content')

{{-- ===================== PRELOADER ===================== --}}
<div id="preloader" aria-hidden="true">
    <div class="text-center">
        <div class="pre-xd t-graffiti chrome scale-50 text-[22vw] opacity-0 leading-none sm:text-[12rem]">XD</div>
        <div class="mx-auto mt-2 h-2 w-48 origin-left scale-x-0 bg-neon pre-bar"></div>
    </div>
</div>

{{-- ===================== ΤΟ 3D ΤΑΞΙΔΙ (hero + σκηνές) ===================== --}}
<section id="journey" class="journey relative bg-ink" style="height: 880svh">
    <div id="hero" class="journey-stage vignette">
        <canvas class="journey-canvas absolute inset-0 h-full w-full" aria-hidden="true"></canvas>

        {{-- Βροχή, αστραπές --}}
        <canvas id="rain" class="absolute inset-0 h-full w-full opacity-70" aria-hidden="true"></canvas>
        <svg class="hero-bolt" aria-hidden="true" preserveAspectRatio="none"><path d=""/></svg>
        <div class="hero-flash" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-ink to-transparent"></div>

        {{-- Σταθμός 0: τίτλος --}}
        <div class="caption" data-station="0">
            <div class="mx-auto w-full max-w-7xl px-4 pt-24 sm:px-6">
                <div class="hero-title max-w-4xl" style="transform-style: preserve-3d">
                    <p class="t-marker spray-white mb-2 text-2xl sm:text-3xl">5D Cinema Προσομοιωτής</p>
                    <h1 class="m-0">
                        <img class="hero-logo" src="{{ asset('img/gen/logo-wildstyle.png') }}" alt="XD Cinema" width="1400" height="768" fetchpriority="high">
                        <span class="sr-only">XD Cinema</span>
                    </h1>
                    <p class="t-display spray drips mt-1 text-[8.2vw] sm:text-6xl lg:text-7xl">
                        Ζήσε την απόλυτη εμπειρία!
                        <span class="drip" style="left:18%;--h:38px;animation-delay:1.3s"></span>
                        <span class="drip" style="left:47%;--h:62px;animation-delay:1.7s"></span>
                        <span class="drip" style="left:71%;--h:28px;animation-delay:2.1s"></span>
                    </p>
                    <p class="mt-5 max-w-xl text-lg text-chrome/85 drop-shadow-[0_2px_2px_#000] sm:text-xl">
                        Κινούμενα καθίσματα, 3D εικόνα, αέρας, νερό, χιόνι, καπνός. Ο κινητός 5D προσομοιωτής που έρχεται στην εκδήλωσή σου.
                    </p>
                </div>
                <div class="hero-cta mt-7 flex flex-wrap gap-4">
                    <a href="#contact" class="btn-neon text-lg">Κλείσε την εμπειρία</a>
                    <a href="#experience" class="btn-ghost text-lg">Τι είναι το 5D;</a>
                </div>
            </div>
        </div>

        {{-- Σταθμοί 1-6: σκηνές --}}
        @foreach ([
            ['Η κατάρα του πειρατή', 'Σπηλιά, σεντούκι με χρυσάφι και μια σκελετωμένη παλάμη που το φυλάει. Νιώσε τις σταγόνες στο πρόσωπο.', 'Νερό · Αέρας · Αστραπή', 'sea', 'end', 3],
            ['Μεγαλόδοντας', 'Βγαίνει από τα βαθιά με τα σαγόνια ανοιχτά, κατευθείαν πάνω σου.', 'Φυσαλίδες · Νερό · Κίνηση', 'sea', 'start', 4],
            ['Η οργή του Κονγκ', 'Ελικόπτερα, φωτιά, συντρίμμια. Ο βασιλιάς σκαρφαλώνει πάνω από την πόλη.', 'Καπνός · Δόνηση · Αέρας', 'ember', 'start', 4],
            ['Πόλη θρίλερ', 'Βροχή, νέον, ατμός. Κάποιος σε περιμένει στο τέλος του στενού.', 'Καπνός · Αστραπή · Ήχος', 'blood', 'end', 5],
            ['Σπάει τον τοίχο!', 'Ο T-Rex ορμάει μέσα από τον τοίχο με τα graffiti. Το κάθισμα τραντάζεται, τα τούβλα πετάγονται.', 'Κίνηση · Δόνηση · Καπνός', 'neon', 'center', 5],
            ['Roller Coaster', 'Λούπες, πτώσεις, ταχύτητα. Το κλασικό που κάνει όλους να ουρλιάζουν.', 'Κίνηση · Αέρας', 'neon', 'end', 3],
        ] as $i => [$title, $desc, $fx, $color, $align, $level])
        <div class="caption items-end pb-20" data-station="{{ $i + 1 }}">
            <div class="mx-auto flex w-full max-w-7xl px-4 sm:px-6 {{ ['start' => 'justify-start', 'center' => 'justify-center text-center', 'end' => 'justify-end text-right'][$align] }}">
                <div class="max-w-xl">
                    <span class="t-marker inline-block -rotate-3 bg-{{ $color }} px-3 py-1 text-lg text-black">{{ $fx }}</span>
                    <h2 class="t-display mt-3 text-6xl text-white drop-shadow-[0_6px_0_#000] sm:text-8xl">{{ $title }}</h2>
                    <p class="mt-3 text-lg text-chrome/85 drop-shadow-[0_2px_2px_#000] sm:text-xl">{{ $desc }}</p>
                    <p class="mt-3 flex items-center gap-3 {{ $align === 'end' ? 'justify-end' : ($align === 'center' ? 'justify-center' : '') }}">
                        <span class="t-display text-lg text-chrome/70">Ένταση</span>
                        <span class="meter" aria-label="Ένταση {{ $level }} από 5">@for ($b = 1; $b <= 5; $b++)<i class="{{ $b <= $level ? 'on' : '' }}"></i>@endfor</span>
                    </p>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Σταθμός 7: κάλεσμα --}}
        <div class="caption items-center" data-station="7">
            <div class="mx-auto w-full max-w-5xl px-4 text-center sm:px-6">
                <p class="t-marker text-2xl text-neon">Έτοιμος;</p>
                <h2 class="t-display mt-2 text-7xl text-white drop-shadow-[0_6px_0_#000] sm:text-9xl">Φέρε το <span class="fire t-graffiti">5D</span><br>στην εκδήλωσή σου</h2>
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="#contact" class="btn-neon text-xl">Κλείσε θέση</a>
                    <a href="#effects" class="btn-ghost text-xl">Δες τα εφέ</a>
                </div>
            </div>
        </div>

        {{-- Δείκτης προόδου --}}
        <div class="journey-progress" aria-hidden="true"><span></span></div>

        <a id="scroll-hint" href="#experience" class="absolute bottom-6 left-1/2 z-10 -translate-x-1/2 text-chrome/70 hover:text-neon" aria-label="Κύλιση προς τα κάτω">
            <svg class="animate-bounce" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 4v16m0 0l-6-6m6 6l6-6"/></svg>
        </a>
    </div>
</section>

{{-- ===================== ΤΑΙΝΙΑ ΕΦΕ (marquee) ===================== --}}
<div class="tape relative z-20 -mt-6 py-1.5" aria-hidden="true">
    <div class="tape-inner marquee py-3">
        @for ($i = 0; $i < 2; $i++)
        <div class="marquee__track t-display text-3xl">
            @foreach (['Αέρας', 'Νερό', 'Χιόνι', 'Φυσαλίδες', 'Κίνηση', 'Καπνός', 'Αστραπή', '3D εικόνα', 'Ήχος surround'] as $w)
                <span>{{ $w }}</span><span>✦</span>
            @endforeach
        </div>
        @endfor
    </div>
</div>

{{-- ===================== Η ΕΜΠΕΙΡΙΑ ===================== --}}
<section id="experience" class="wall overflow-hidden py-28" style="background-image:url('{{ asset('img/gen/texture-graffiti-wall.jpg') }}')">
    <div id="wall" class="relative mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2">
        <div class="shake relative">
            <p class="t-marker text-xl text-neon" data-reveal>Τι είναι το XD 5D;</p>
            <h2 class="t-display mt-2 text-6xl text-white sm:text-7xl lg:text-8xl" data-reveal>
                Δεν βλέπεις<br>την ταινία.<br><span class="stencil">Τη ζεις.</span>
            </h2>
            <p class="mt-6 max-w-lg text-lg text-chrome/80" data-reveal>
                Το 5D cinema συνδυάζει 3D προβολή με καθίσματα που κινούνται σε συγχρονισμό με τη δράση
                και ειδικά εφέ που νιώθεις στο σώμα σου: ριπές αέρα, ψεκασμό νερού, χιόνι, φυσαλίδες και καπνό.
                Όταν ο T-Rex ορμάει, το κάθισμα ορμάει μαζί του.
            </p>
            <ul class="mt-8 space-y-4" data-reveal>
                @foreach ([
                    ['Κινούμενα καθίσματα', 'Πλατφόρμα που ακολουθεί κάθε στροφή, πτώση και πρόσκρουση.'],
                    ['3D γυαλιά & οθόνη', 'Στερεοσκοπική προβολή με ήχο που σε περικυκλώνει.'],
                    ['Ειδικά εφέ 5D', 'Αέρας, νερό, χιόνι, φυσαλίδες, καπνός, αστραπές, όλα συγχρονισμένα με την ταινία.'],
                ] as [$t, $d])
                <li class="flex gap-4">
                    <span class="mt-1 grid h-8 w-8 shrink-0 place-items-center border-2 border-neon bg-black t-display text-neon">{{ $loop->iteration }}</span>
                    <div><strong class="t-display text-2xl text-white">{{ $t }}</strong><p class="text-chrome/70">{{ $d }}</p></div>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="relative" data-reveal data-tilt>
            <div class="absolute -inset-6 bg-neon/10 blur-3xl"></div>
            <img id="trex" class="relative w-full border-4 border-black shadow-[14px_14px_0_#000]" src="{{ asset('img/gen/experience-seats.jpg') }}" alt="Θεατές με 3D γυαλιά στα κινούμενα καθίσματα του XD Cinema, με χιόνι και φυσαλίδες στον αέρα" loading="lazy">
            <img class="absolute -bottom-10 -left-4 w-2/5 -rotate-3 border-4 border-black shadow-[10px_10px_0_#000]" src="{{ asset('img/gen/audience-kids.jpg') }}" alt="Παιδιά με 3D γυαλιά ουρλιάζουν από ενθουσιασμό" loading="lazy">
            <span class="t-marker spray-blood absolute -top-6 -right-2 rotate-[5deg] text-4xl sm:text-5xl">Το νιώθεις!</span>
        </div>
    </div>
</section>

{{-- ===================== ΤΑ ΕΦΕ ===================== --}}
<section id="effects" class="relative overflow-hidden bg-asphalt py-28">
    <canvas id="embers" class="pointer-events-none absolute inset-0 h-full w-full opacity-70" aria-hidden="true"></canvas>
    <div class="pointer-events-none absolute -left-24 top-10 h-72 w-72 rounded-full bg-sea/20 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-24 bottom-10 h-72 w-72 rounded-full bg-ember/20 blur-3xl"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6">
        <p class="t-marker text-xl text-neon" data-reveal>Τα εφέ</p>
        <h2 class="t-display mt-2 text-6xl text-white sm:text-7xl lg:text-8xl" data-reveal>Το νιώθεις <span class="spray">παντού</span></h2>
        <p class="t-marker mt-4 text-xl text-chrome/80" data-reveal>Πάτησε μια κάρτα για να δεις το εφέ στην οθόνη σου 👇</p>

        <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['Αέρας', 'Ριπές ανέμου στο πρόσωπο όταν η ταχύτητα ανεβαίνει.', 'icon-wind', 'splat-yellow', 'wind'],
                ['Νερό', 'Ψεκασμός νερού όταν το κύμα σπάει πάνω σου.', 'icon-water', 'splat-white', 'water'],
                ['Χιόνι', 'Νιφάδες πέφτουν μέσα στην αίθουσα.', 'icon-snow', 'splat-white', 'snow'],
                ['Φυσαλίδες', 'Φυσαλίδες γεμίζουν τον αέρα στις υποβρύχιες σκηνές.', 'icon-bubbles', 'splat-yellow', 'bubbles'],
                ['Κίνηση', 'Το κάθισμα γέρνει, πέφτει, τραντάζεται σε κάθε σκηνή.', 'icon-motion', 'splat-red', 'shake'],
                ['Καπνός', 'Ομίχλη και καπνός κάνουν το σκοτάδι πιο τρομακτικό.', 'icon-smoke', 'splat-white', 'smoke'],
                ['Αστραπή', 'Φλας φωτός και βροντές σε συγχρονισμό με την καταιγίδα.', 'icon-lightning', 'splat-yellow', 'lightning'],
                ['Δόνηση', 'Παλμοί κάτω από τα πόδια και στην πλάτη.', 'icon-motion', 'splat-red', 'vibrate'],
            ] as [$name, $desc, $icon, $splat, $fx])
            <article class="fx-card p-6" data-tilt data-reveal data-fx="{{ $fx }}" role="button" tabindex="0" aria-label="Δοκίμασε το εφέ: {{ $name }}">
                <img class="splat-img" src="{{ asset('img/gen/'.$splat.'.png') }}" alt="" aria-hidden="true" loading="lazy">
                <img class="fx-icon relative" src="{{ asset('img/gen/'.$icon.'.png') }}" alt="" loading="lazy">
                <h3 class="t-display relative mt-4 text-3xl text-white">{{ $name }}</h3>
                <p class="relative mt-2 text-chrome/70">{{ $desc }}</p>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== ΟΙ ΤΑΙΝΙΕΣ ===================== --}}
<section id="movies" class="relative overflow-hidden bg-ink py-28">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-neon/60 to-transparent"></div>
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-6">
            <div>
                <p class="t-marker text-xl text-neon" data-reveal>Οι ταινίες</p>
                <h2 class="t-display mt-2 text-6xl text-white sm:text-7xl lg:text-8xl" data-reveal>Διάλεξε τον <span class="spray-blood">εφιάλτη</span> σου</h2>
            </div>
            <p class="max-w-md text-chrome/75" data-reveal>Μικρής διάρκειας περιπέτειες, ώστε να περνούν πολλοί θεατές την ώρα. Η ένταση δείχνει πόσο «άγρια» κινείται το κάθισμα.</p>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ([
                ['Η κατάρα του πειρατή', 'gen/scene-treasure.jpg', 'Μια σπηλιά γεμάτη χρυσάφι και ένας θησαυρός που δεν θέλει να φύγει από τη θέση του.', 3, '5+', 'Νερό, αέρας, αστραπή'],
                ['Μεγαλόδοντας', 'gen/scene-shark.jpg', 'Κατάδυση στα βαθιά. Κάτι τεράστιο σε έχει ήδη μυρίσει.', 4, '8+', 'Φυσαλίδες, νερό, κίνηση'],
                ['Η οργή του Κονγκ', 'gen/scene-kong-city.jpg', 'Η πόλη καίγεται και ο βασιλιάς ανεβαίνει στον ουρανοξύστη.', 4, '8+', 'Καπνός, δόνηση, αέρας'],
                ['Πόλη θρίλερ', 'gen/scene-city.jpg', 'Νέον, βροχή και μια σκιά που σε ακολουθεί στα στενά.', 5, '12+', 'Καπνός, αστραπή'],
                ['T-Rex: Σπάει τον τοίχο', 'gen/trex-graffiti-wall.jpg', 'Ο τοίχος δεν τον κρατάει. Τα τούβλα έρχονται κατά πάνω σου.', 5, '8+', 'Κίνηση, δόνηση, καπνός'],
                ['Roller Coaster', 'layer-coaster.jpg', 'Λούπες, ανηφόρες, ελεύθερη πτώση. Το κλασικό που δεν χορταίνεις.', 3, '5+', 'Κίνηση, αέρας'],
                ['Τσουνάμι', 'gen/hero-tsunami.jpg', 'Το κύμα σηκώνεται πάνω από τους ουρανοξύστες. Τρέξε.', 4, '8+', 'Νερό, αέρας, αστραπή'],
                ['Κινγκ Κονγκ: Η ζούγκλα', 'layer-kong.jpg', 'Φωτιά, εκρήξεις και ένας γορίλας που δεν αστειεύεται.', 4, '8+', 'Καπνός, δόνηση'],
            ] as [$title, $img, $desc, $level, $age, $fx])
            <article class="movie" data-tilt data-reveal>
                <div class="movie-img">
                    <img src="{{ asset('img/'.$img) }}" alt="{{ $title }}" loading="lazy">
                    <span class="movie-age">{{ $age }}</span>
                </div>
                <div class="relative -mt-16 p-5">
                    <h3 class="t-display text-3xl text-white">{{ $title }}</h3>
                    <p class="mt-2 text-sm text-chrome/75">{{ $desc }}</p>
                    <dl class="mt-4">
                        <dt>Ένταση</dt><dd><span class="meter" aria-label="Ένταση {{ $level }} από 5">@for ($b = 1; $b <= 5; $b++)<i class="{{ $b <= $level ? 'on' : '' }}"></i>@endfor</span></dd>
                        <dt>Εφέ</dt><dd class="text-chrome/85">{{ $fx }}</dd>
                        <dt>Ηλικία</dt><dd class="text-chrome/85">{{ $age }} με συνοδό</dd>
                    </dl>
                </div>
            </article>
            @endforeach
        </div>
        <p class="mt-8 text-sm text-chrome/55" data-reveal>Το πρόγραμμα ταινιών προσαρμόζεται στο κοινό της κάθε εκδήλωσης (παιδικό, εφηβικό, μεικτό).</p>
    </div>
</section>

{{-- ===================== ΠΩΣ ΛΕΙΤΟΥΡΓΕΙ ===================== --}}
<section id="how" class="relative overflow-hidden bg-asphalt py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <p class="t-marker text-xl text-neon" data-reveal>Πώς λειτουργεί</p>
        <h2 class="t-display mt-2 text-6xl text-white sm:text-7xl lg:text-8xl" data-reveal>Τέσσερα βήματα<br>μέχρι το <span class="spray">ουρλιαχτό</span></h2>
        <div class="mt-16 grid gap-8 md:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['Μπες στην καμπίνα', 'Ο χειριστής σε οδηγεί στη θέση σου μέσα στον προσομοιωτή.', 'icon-smoke'],
                ['Γυαλιά & ζώνη', 'Φοράς τα 3D γυαλιά, δένεις τη ζώνη και κρατάς τις χειρολαβές.', 'icon-motion'],
                ['Διάλεξε περιπέτεια', 'Πειρατές, καρχαρίες, T-Rex ή roller coaster. Το κοινό αποφασίζει.', 'icon-lightning'],
                ['Κρατήσου!', 'Τα καθίσματα ζωντανεύουν και τα εφέ χτυπούν συγχρονισμένα με την οθόνη.', 'icon-wind'],
            ] as [$t, $d, $icon])
            <div class="step" data-reveal>
                <span class="step-n">{{ $loop->iteration }}</span>
                <img src="{{ asset('img/gen/'.$icon.'.png') }}" alt="" loading="lazy">
                <h3 class="t-display mt-4 text-3xl text-white">{{ $t }}</h3>
                <p class="mt-2 text-chrome/75">{{ $d }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== ΓΚΑΛΕΡΙ ===================== --}}
<section id="gallery" class="wall overflow-hidden py-28" style="background-image:url('{{ asset('img/gen/texture-graffiti-wall.jpg') }}')">
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6">
        <p class="t-marker text-xl text-neon" data-reveal>Γκαλερί</p>
        <h2 class="t-display mt-2 text-6xl text-white sm:text-7xl lg:text-8xl" data-reveal>Μέσα στη <span class="spray">δράση</span></h2>
        <div class="gal mt-12" data-reveal>
            @foreach ([
                ['trailer-side.jpg', 'Η καμπίνα XD Cinema', 'tall'],
                ['gen/experience-seats.jpg', 'Τα κινούμενα καθίσματα', ''],
                ['gen/audience-kids.jpg', 'Οι μικροί θεατές', ''],
                ['trailer-back.jpg', 'Ο T-Rex στην πίσω όψη', 'tall'],
                ['gen/trailer-festival-night.jpg', 'Βράδυ σε φεστιβάλ', ''],
                ['gen/trex-graffiti-wall.jpg', 'Σπάει τον τοίχο', ''],
                ['poster-lid.jpg', 'Το artwork της καμπίνας', ''],
                ['gen/logo-wall.jpg', 'XD graffiti', ''],
            ] as [$img, $cap, $cls])
            <a href="{{ asset('img/'.$img) }}" class="{{ $cls }}" data-lightbox="{{ asset('img/'.$img) }}" data-caption="{{ $cap }}">
                <img src="{{ asset('img/'.$img) }}" alt="{{ $cap }}" loading="lazy">
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== ΕΚΔΗΛΩΣΕΙΣ ===================== --}}
<section id="events" class="relative overflow-hidden bg-concrete py-28">
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2">
        <div class="relative" data-reveal data-tilt>
            <img class="w-full border-4 border-black shadow-[14px_14px_0_#000]" src="{{ asset('img/gen/trailer-festival-night.jpg') }}" alt="Η καμπίνα του XD Cinema σε νυχτερινό φεστιβάλ με ουρά κόσμου" loading="lazy">
            <img class="absolute -bottom-10 -right-4 w-2/5 rotate-3 border-4 border-black shadow-[10px_10px_0_#000] sm:-right-10" src="{{ asset('img/trailer-side.jpg') }}" alt="Η πραγματική καμπίνα του XD Cinema με το artwork του πειρατή και του King Kong" loading="lazy">
        </div>
        <div>
            <p class="t-marker text-xl text-neon" data-reveal>Ερχόμαστε σε εσένα</p>
            <h2 class="t-display mt-2 text-6xl text-white sm:text-7xl lg:text-8xl" data-reveal>Το cinema <span class="spray">ταξιδεύει</span></h2>
            <p class="mt-6 max-w-lg text-lg text-chrome/80" data-reveal>
                Ο προσομοιωτής είναι αυτόνομη κινητή μονάδα. Στήνεται σε λίγες ώρες σε πλατείες, πανηγύρια,
                φεστιβάλ, σχολεία, εμπορικά κέντρα, εταιρικές εκδηλώσεις και παιδικά πάρτι.
            </p>
            <div class="mt-10 grid grid-cols-3 gap-4" data-reveal>
                <div><div class="stat text-neon"><span data-count="8">0</span></div><p class="t-display text-xl text-chrome/70">Εφέ 5D</p></div>
                <div><div class="stat text-neon"><span data-count="6">0</span>+</div><p class="t-display text-xl text-chrome/70">Περιπέτειες</p></div>
                <div><div class="stat text-neon">100<span class="text-4xl">%</span></div><p class="t-display text-xl text-chrome/70">Αδρεναλίνη</p></div>
            </div>
            <div class="mt-10 flex flex-wrap gap-3" data-reveal>
                @foreach (['Πανηγύρια', 'Φεστιβάλ', 'Σχολεία', 'Εμπορικά κέντρα', 'Εταιρικά', 'Παιδικά πάρτι', 'Δήμοι'] as $tag)
                    <span class="t-display border-2 border-white/25 px-3 py-1 text-lg text-chrome/85">{{ $tag }}</span>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ===================== ΓΙΑ ΔΙΟΡΓΑΝΩΤΕΣ ===================== --}}
<section id="organizers" class="relative overflow-hidden bg-asphalt py-28">
    <div class="pointer-events-none absolute -right-32 top-0 h-96 w-96 rounded-full bg-neon/10 blur-3xl"></div>
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <p class="t-marker text-xl text-neon" data-reveal>Για διοργανωτές</p>
        <h2 class="t-display mt-2 text-6xl text-white sm:text-7xl lg:text-8xl" data-reveal>Εσύ δίνεις τον χώρο,<br>εμείς τον <span class="spray">πανικό</span></h2>
        <div class="mt-12 grid gap-6 lg:grid-cols-3">
            <div class="border-4 border-black bg-ink p-7 shadow-[10px_10px_0_#000]" data-reveal>
                <h3 class="t-display text-3xl text-neon">Τι φέρνουμε εμείς</h3>
                <ul class="check mt-5 space-y-3 text-chrome/85">
                    <li>Την πλήρη κινητή μονάδα 5D με κινούμενα καθίσματα, 3D προβολή και όλα τα εφέ.</li>
                    <li>Χειριστή σε όλη τη διάρκεια της εκδήλωσης, για ασφάλεια και ροή.</li>
                    <li>3D γυαλιά για όλους τους θεατές, απολυμασμένα μετά από κάθε προβολή.</li>
                    <li>Πρόγραμμα ταινιών προσαρμοσμένο στο κοινό σου.</li>
                    <li>Ηχοσύστημα, φωτισμό και το εντυπωσιακό artwork της καμπίνας που τραβάει κόσμο.</li>
                </ul>
            </div>
            <div class="border-4 border-black bg-ink p-7 shadow-[10px_10px_0_#000]" data-reveal>
                <h3 class="t-display text-3xl text-neon">Τι χρειαζόμαστε από εσένα</h3>
                <ul class="check mt-5 space-y-3 text-chrome/85">
                    <li>Επίπεδο σημείο στάθμευσης για το τρέιλερ, με πρόσβαση για όχημα.</li>
                    <li>Παροχή ρεύματος κοντά στο σημείο (ή συνεννόηση για γεννήτρια).</li>
                    <li>Λίγες ώρες πριν την έναρξη για στήσιμο και δοκιμές.</li>
                    <li>Τις ημερομηνίες και το εκτιμώμενο κοινό, για να υπολογίσουμε βάρδιες.</li>
                </ul>
            </div>
            <div class="flex flex-col justify-between border-4 border-black bg-neon p-7 text-black shadow-[10px_10px_0_#000]" data-reveal>
                <div>
                    <h3 class="t-display text-3xl">Ιδανικό για</h3>
                    <ul class="mt-5 grid grid-cols-2 gap-2 t-display text-xl">
                        @foreach (['Πανηγύρια', 'Φεστιβάλ', 'Σχολεία', 'Εμπορικά κέντρα', 'Δήμους', 'Εταιρικά', 'Παιδικά πάρτι', 'Λούνα παρκ'] as $t)
                            <li class="border-b-2 border-black/30 pb-1">{{ $t }}</li>
                        @endforeach
                    </ul>
                </div>
                <a href="#contact" class="btn-ghost mt-8 justify-center !border-black !bg-black !text-neon hover:!bg-ink">Ζήτα προσφορά</a>
            </div>
        </div>
    </div>
</section>

{{-- ===================== ΣΥΧΝΕΣ ΕΡΩΤΗΣΕΙΣ ===================== --}}
<section id="faq" class="relative overflow-hidden bg-ink py-28">
    <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-[1fr_1.4fr]">
        <div>
            <p class="t-marker text-xl text-neon" data-reveal>Συχνές ερωτήσεις</p>
            <h2 class="t-display mt-2 text-6xl text-white sm:text-7xl lg:text-8xl" data-reveal>Πριν <span class="stencil">μπεις</span></h2>
            <p class="mt-6 max-w-md text-chrome/75" data-reveal>Δεν βρήκες αυτό που ψάχνεις; Γράψε μας στη φόρμα και θα σου απαντήσουμε άμεσα.</p>
            <img class="mt-8 hidden w-full max-w-sm border-4 border-black shadow-[10px_10px_0_#000] lg:block" src="{{ asset('img/gen/audience-kids.jpg') }}" alt="" loading="lazy">
        </div>
        <div class="faq space-y-3" data-reveal>
            @foreach ([
                ['Από ποια ηλικία μπορεί να μπει ένα παιδί;', 'Οι ήπιες περιπέτειες είναι κατάλληλες από την προσχολική ηλικία με συνοδό. Οι πιο έντονες (ένταση 4 και 5) προτείνονται για μεγαλύτερα παιδιά και ενήλικες. Ο χειριστής προτείνει πάντα την κατάλληλη ταινία.'],
                ['Πόσο διαρκεί μια προβολή;', 'Κάθε περιπέτεια διαρκεί λίγα λεπτά. Έτσι η ουρά προχωράει γρήγορα και περνούν πολλοί θεατές μέσα σε μία ώρα.'],
                ['Είναι ασφαλές;', 'Τα καθίσματα έχουν ζώνες και χειρολαβές, και ο χειριστής επιβλέπει κάθε προβολή. Δεν προτείνεται σε εγκύους, σε άτομα με καρδιολογικά προβλήματα, προβλήματα στη μέση ή τον αυχένα, επιληψία, ή σε όσους έχουν κάνει πρόσφατα χειρουργείο.'],
                ['Θα βραχώ;', 'Μόνο λίγο. Ο ψεκασμός νερού είναι ελαφρύς και στιγμιαίος, όσο χρειάζεται για να νιώσεις το κύμα. Τα ρούχα δεν μουσκεύουν.'],
                ['Χρειάζομαι δικά μου 3D γυαλιά;', 'Όχι. Παρέχουμε 3D γυαλιά σε όλους τους θεατές και τα απολυμαίνουμε μετά από κάθε προβολή.'],
                ['Τι χώρο χρειάζεται η μονάδα;', 'Ένα επίπεδο σημείο για το τρέιλερ με πρόσβαση οχήματος και παροχή ρεύματος. Στείλε μας το σημείο και θα σου πούμε αν ταιριάζει, συνήθως ταιριάζει.'],
                ['Πόσο κοστίζει;', 'Η τιμή εξαρτάται από τις ημέρες, τις ώρες λειτουργίας και την απόσταση. Συμπλήρωσε τη φόρμα και θα λάβεις προσφορά χωρίς δέσμευση.'],
            ] as [$q, $a])
            <details>
                <summary>{{ $q }}</summary>
                <div class="faq-body">{{ $a }}</div>
            </details>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== ΕΠΙΚΟΙΝΩΝΙΑ ===================== --}}
<section id="contact" class="relative overflow-hidden bg-ink py-28">
    <img class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-35" src="{{ asset('img/gen/logo-wall.jpg') }}" alt="" loading="lazy">
    <div class="absolute inset-0 bg-gradient-to-b from-ink via-ink/60 to-ink"></div>
    <div class="relative mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-2">
        <div>
            <p class="t-marker text-xl text-neon" data-reveal>Επικοινωνία</p>
            <h2 class="t-display mt-2 text-6xl text-white sm:text-7xl lg:text-8xl" data-reveal>Πες μας πού και πότε</h2>
            <p class="mt-6 max-w-lg text-lg text-chrome/80" data-reveal>
                Θα σου απαντήσουμε με διαθεσιμότητα και προσφορά για την εκδήλωσή σου.
            </p>
            <ul class="mt-8 space-y-3 text-lg text-chrome/85" data-reveal>
                <li><a class="hover:text-neon" href="mailto:info@xdcinema.gr">info@xdcinema.gr</a></li>
                <li><a class="hover:text-neon" href="https://www.xdcinema.gr">www.xdcinema.gr</a></li>
            </ul>
        </div>

        <form method="post" action="{{ route('contact.send') }}" class="border-4 border-black bg-asphalt/90 p-6 shadow-[14px_14px_0_#000] backdrop-blur sm:p-8" data-reveal>
            @csrf
            @if (session('ok'))
                <div id="flash" class="mb-5 flex items-start justify-between gap-4 border-2 border-toxic bg-toxic/10 p-4 text-toxic">
                    <p>Το μήνυμά σου στάλθηκε. Θα επικοινωνήσουμε σύντομα!</p>
                    <button type="button" id="flash-close" aria-label="Κλείσιμο">✕</button>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-5 border-2 border-blood bg-blood/10 p-4 text-sm text-white">
                    <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block"><span class="t-display text-lg text-chrome/70">Όνομα *</span>
                    <input name="name" value="{{ old('name') }}" required class="mt-1 w-full border-2 border-white/20 bg-black/60 px-3 py-2.5 text-white focus:border-neon focus:outline-none"></label>
                <label class="block"><span class="t-display text-lg text-chrome/70">Τηλέφωνο *</span>
                    <input name="phone" value="{{ old('phone') }}" required class="mt-1 w-full border-2 border-white/20 bg-black/60 px-3 py-2.5 text-white focus:border-neon focus:outline-none"></label>
                <label class="block sm:col-span-2"><span class="t-display text-lg text-chrome/70">Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full border-2 border-white/20 bg-black/60 px-3 py-2.5 text-white focus:border-neon focus:outline-none"></label>
                <label class="block"><span class="t-display text-lg text-chrome/70">Είδος εκδήλωσης</span>
                    <select name="type" class="mt-1 w-full border-2 border-white/20 bg-black/60 px-3 py-2.5 text-white focus:border-neon focus:outline-none">
                        @foreach (['Πανηγύρι / Φεστιβάλ', 'Σχολείο', 'Εμπορικό κέντρο', 'Εταιρική εκδήλωση', 'Παιδικό πάρτι', 'Άλλο'] as $t)
                            <option @selected(old('type') === $t)>{{ $t }}</option>
                        @endforeach
                    </select></label>
                <label class="block"><span class="t-display text-lg text-chrome/70">Ημερομηνία</span>
                    <input type="date" name="date" value="{{ old('date') }}" class="mt-1 w-full border-2 border-white/20 bg-black/60 px-3 py-2.5 text-white focus:border-neon focus:outline-none"></label>
                <label class="block sm:col-span-2"><span class="t-display text-lg text-chrome/70">Μήνυμα</span>
                    <textarea name="message" rows="4" class="mt-1 w-full border-2 border-white/20 bg-black/60 px-3 py-2.5 text-white focus:border-neon focus:outline-none">{{ old('message') }}</textarea></label>
                <div class="hidden" aria-hidden="true"><input name="website" tabindex="-1" autocomplete="off"></div>
            </div>
            <button type="submit" class="btn-neon mt-6 w-full justify-center text-xl">Στείλε το αίτημα</button>
        </form>
    </div>
</section>

@endsection
