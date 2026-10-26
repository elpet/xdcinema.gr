<footer class="relative border-t border-white/10 bg-black">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-3">
        <div>
            <div class="flex items-baseline gap-2">
                <span class="t-graffiti chrome text-4xl">XD</span>
                <span class="t-display text-2xl">CINEMA</span>
                <span class="t-marker fire text-xl">5D</span>
            </div>
            <p class="mt-3 max-w-xs text-smoke">Κινητός 5D cinema προσομοιωτής. Ερχόμαστε όπου είναι η εκδήλωσή σου.</p>
        </div>
        <div>
            <h3 class="t-display text-xl text-neon">Σελίδα</h3>
            <ul class="mt-3 space-y-2 text-smoke">
                <li><a href="#experience" class="hover:text-chrome">Η εμπειρία</a></li>
                <li><a href="#effects" class="hover:text-chrome">Τα εφέ</a></li>
                <li><a href="#movies" class="hover:text-chrome">Ταινίες</a></li>
                <li><a href="#events" class="hover:text-chrome">Εκδηλώσεις</a></li>
                <li><a href="#faq" class="hover:text-chrome">Ερωτήσεις</a></li>
                <li><a href="#contact" class="hover:text-chrome">Επικοινωνία</a></li>
            </ul>
        </div>
        <div>
            <h3 class="t-display text-xl text-neon">Επικοινωνία</h3>
            <ul class="mt-3 space-y-2 text-smoke">
                <li><a href="https://www.xdcinema.gr" class="hover:text-chrome">www.xdcinema.gr</a></li>
                <li><a href="mailto:info@xdcinema.gr" class="hover:text-chrome">info@xdcinema.gr</a></li>
            </ul>
            <div class="mt-5 flex gap-3">
                @foreach ([
                    ['Facebook', 'https://www.facebook.com/', 'M14 8h3V4h-3c-2.8 0-4 1.7-4 4v2H7v4h3v8h4v-8h3l1-4h-4V8z'],
                    ['Instagram', 'https://www.instagram.com/', 'M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4zm5 5a4 4 0 1 0 0 8 4 4 0 0 0 0-8zm5-1.5a1 1 0 1 0 0 2 1 1 0 0 0 0-2z'],
                    ['TikTok', 'https://www.tiktok.com/', 'M14 3h3a4 4 0 0 0 4 4v3a7 7 0 0 1-4-1.3V15a6 6 0 1 1-6-6v3a3 3 0 1 0 3 3V3z'],
                ] as [$n, $u, $d])
                <a href="{{ $u }}" target="_blank" rel="noopener" aria-label="{{ $n }}" class="grid h-11 w-11 place-items-center border-2 border-white/25 text-chrome/80 transition hover:border-neon hover:text-neon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="{{ $d }}"/></svg>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    <a href="#hero" class="absolute -top-6 right-6 grid h-12 w-12 place-items-center border-[3px] border-black bg-neon text-black shadow-[4px_4px_0_#000] transition hover:-translate-y-1" aria-label="Επιστροφή στην κορυφή">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M12 20V4m0 0l-6 6m6-6l6 6"/></svg>
    </a>
    <div class="border-t border-white/10 py-5 text-center text-sm text-smoke/70">
        © {{ date('Y') }} XD Cinema. Με επιφύλαξη παντός δικαιώματος.
    </div>
</footer>
