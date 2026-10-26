# CLAUDE.md — XD Cinema (xdcinema.gr)

Site για τον κινητό 5D cinema προσομοιωτή «XD CINEMA». Ύφος: σκοτεινό, κινηματογραφικό, graffiti/street.

## Stack
- **Laravel 12**, PHP ≥ 8.2 (στον server Papaki μαζί με το almazidis.com, PHP 8.3). Χωρίς βάση δεδομένων προς το παρόν (sessions/cache σε αρχεία, queue sync).
- **Vite + Tailwind 4** (`resources/css/app.css` με `@theme` tokens), vanilla JS με **GSAP + ScrollTrigger** και **Lenis**.
- Γραμματοσειρές self-hosted μέσω `@fontsource` (όχι Google Fonts, GDPR). Ελληνικοί τίτλοι: Sofia Sans Extra Condensed 900. Λατινικά graffiti: Bangers, Permanent Marker.
- Εικόνες από τα εκτυπωτικά της καμπίνας στο `public/img/` (layer-*.jpg για parallax, trex-wall, poster-lid, trailer-*).

## Εντολές
```bash
npm install && npm run build      # παραγωγή assets (public/build)
npm run dev                        # Vite dev server
php artisan serve                  # http://127.0.0.1:8000
php artisan test
```

## Δομή
- `routes/web.php` — `/` (home) και `POST /contact` (ContactController, throttle 5/10min, honeypot `website`).
- `resources/views/layouts/app.blade.php` — layout, SVG φίλτρα σπρέι (`#spray-edge`).
- `resources/views/home.blade.php` — ενότητες: preloader, **#journey** (sticky 3D «ταξίδι» με 7 σταθμούς/λεζάντες `data-station`), marquee, εμπειρία, εφέ (κάρτες με `data-fx` που παίζουν επίδειξη εφέ σε όλη την οθόνη), ταινίες (#movies, μετρητής έντασης `.meter`), πώς λειτουργεί (#how), γκαλερί (#gallery, lightbox), εκδηλώσεις, διοργανωτές (#organizers), FAQ (#faq, details/summary), επικοινωνία.
- `resources/js/app.js` — Lenis, preloader/intro, ορισμός σταθμών του ταξιδιού (εικόνες ως επίπεδα με βάθος), reveal, counters, tilt. Φορτώνει το `fx/journey.js` με dynamic import.
- `resources/js/fx/journey.js` — Three.js: κάμερα κινείται στον Z με το scroll (ScrollTrigger progress), επίπεδα εικόνων με vignette alpha, σωματίδια, τούβλα που εκτοξεύονται στον σταθμό του T-Rex, σβήσιμο επιπέδων όταν η κάμερα τα προσπερνά, parallax ποντικιού, κλίμακα 0.62 σε κάθετες οθόνες.
- `resources/js/fx/` — rain, lightning (ενεργή μόνο στο πρώτο 20% του ταξιδιού), embers, parallax/tilt, demo (επίδειξη εφέ: χιόνι/νερό/φυσαλίδες/καπνός/άνεμος/αστραπή/τράνταγμα/δόνηση σε fixed canvas `#fx-stage`), lightbox.
- Στο layout: `#fx-stage`/`#fx-flash`/`#fx-label`, `#lightbox`, `#mobile-cta` (sticky κουμπί κινητού), JSON-LD (τα κλειδιά `@@context`/`@@type` είναι escaped για το Blade).
- `bin/deploy-pages.sh` — build, στατική εξαγωγή και push στο branch `gh-pages` (δημόσια προεπισκόπηση).
- `docs/research-open-source.md` — βιβλιοθήκες, γραμματοσειρές, CC0 υφές που μπορούν να προστεθούν.

## Κανόνες
- Κείμενα στα ελληνικά, σχόλια κώδικα στα ελληνικά, ονόματα στα αγγλικά.
- Κάθε κίνηση σέβεται `prefers-reduced-motion`. Canvas εφέ σταματούν όταν δεν φαίνονται (IntersectionObserver).
- Shared hosting: document root → `public/`, χωρίς queue workers/websockets. Email φόρμας: `MAIL_MAILER` (τοπικά `log`).
- Μην κάνεις commit `.env`, `public/build`, `node_modules`, `vendor`.
