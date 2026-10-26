# XD Cinema — xdcinema.gr

Ιστοσελίδα για τον κινητό 5D cinema προσομοιωτή «XD CINEMA». Laravel 12, Vite, Tailwind 4, GSAP, Lenis, Three.js.

## Τοπική εκτέλεση

```bash
composer install
cp .env.example .env && php artisan key:generate
npm install && npm run build      # ή npm run dev για live reload
php artisan serve                 # http://127.0.0.1:8000
```

## Δημόσια προεπισκόπηση (GitHub Pages)

Στατική εξαγωγή της αρχικής στο branch `gh-pages`: https://elpet.github.io/xdcinema.gr/
Η φόρμα εκεί ανοίγει το email client (δεν τρέχει PHP). Ανανέωση:

```bash
npm run build
php bin/export-static.php ../xdcinema-static https://elpet.github.io/xdcinema.gr
# αντιγραφή του φακέλου στο branch gh-pages και push
```

## Ανέβασμα σε shared hosting (Papaki / cPanel)

1. `npm run build` τοπικά και ανέβασε όλα τα αρχεία **μαζί με** `public/build/` και `vendor/` (ή τρέξε `composer install --no-dev` στον server).
2. Document root του domain → φάκελος `public/`.
3. `.env` στον server: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://www.xdcinema.gr`, ρυθμίσεις SMTP (`MAIL_*`) και `MAIL_FROM_ADDRESS`.
4. Δικαιώματα εγγραφής στα `storage/` και `bootstrap/cache/`.
5. `php artisan config:cache && php artisan route:cache && php artisan view:cache`.

Η φόρμα επικοινωνίας στέλνει email στη διεύθυνση `MAIL_FROM_ADDRESS` (ή `mail.contact_to` αν οριστεί στο `config/mail.php`). Τοπικά τα email γράφονται στο `storage/logs/laravel.log`.

Λεπτομέρειες δομής και κανόνων: `CLAUDE.md`. Έρευνα για βιβλιοθήκες/υφές/γραμματοσειρές: `docs/research-open-source.md`.
