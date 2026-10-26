<header id="nav" class="fixed inset-x-0 top-0 z-50 transition-all duration-300 [&.is-scrolled]:bg-ink/85 [&.is-scrolled]:backdrop-blur-md [&.is-scrolled]:border-b [&.is-scrolled]:border-white/10">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6">
        <a href="#hero" class="flex items-baseline gap-2" aria-label="XD Cinema, αρχική">
            <span class="t-graffiti chrome text-3xl sm:text-4xl">XD</span>
            <span class="t-display text-xl text-chrome sm:text-2xl">CINEMA</span>
            <span class="t-marker fire -ml-1 text-lg sm:text-xl">5D</span>
        </a>

        <nav class="hidden items-center gap-7 lg:flex" aria-label="Κύριο μενού">
            @foreach ([
                '#experience' => 'Η εμπειρία',
                '#effects' => 'Τα εφέ',
                '#movies' => 'Ταινίες',
                '#events' => 'Εκδηλώσεις',
                '#faq' => 'Ερωτήσεις',
                '#contact' => 'Επικοινωνία',
            ] as $href => $label)
                <a href="{{ $href }}" class="t-display text-[1.05rem] tracking-wide text-chrome/80 transition hover:text-neon">{{ $label }}</a>
            @endforeach
            <a href="#contact" class="btn-neon !py-2 !px-4 text-base">Κλείσε θέση</a>
        </nav>

        <button id="burger" class="grid h-11 w-11 place-items-center border-2 border-white/30 lg:hidden" aria-controls="mobile-menu" aria-expanded="false" aria-label="Μενού">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
    </div>

    <div id="mobile-menu" class="hidden border-t border-white/10 bg-ink/95 backdrop-blur-md lg:hidden">
        <nav class="mx-auto flex max-w-7xl flex-col px-4 py-3" aria-label="Μενού κινητού">
            @foreach ([
                '#experience' => 'Η εμπειρία',
                '#effects' => 'Τα εφέ',
                '#movies' => 'Ταινίες',
                '#events' => 'Εκδηλώσεις',
                '#faq' => 'Ερωτήσεις',
                '#contact' => 'Επικοινωνία',
            ] as $href => $label)
                <a href="{{ $href }}" class="t-display border-b border-white/5 py-3 text-2xl text-chrome hover:text-neon">{{ $label }}</a>
            @endforeach
            <a href="#contact" class="btn-neon mt-4 justify-center">Κλείσε θέση</a>
        </nav>
    </div>
</header>
