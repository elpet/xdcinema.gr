<!DOCTYPE html>
<html lang="el" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'XD Cinema · 5D Cinema Προσομοιωτής · Ζήσε την απόλυτη εμπειρία!')</title>
    <meta name="description" content="@yield('description', 'XD CINEMA: κινητός 5D cinema προσομοιωτής με κινούμενα καθίσματα, αέρα, νερό, χιόνι, φυσαλίδες και καπνό. Πειρατές, καρχαρίες, T-Rex, King Kong και roller coaster σε μια απόλυτη εμπειρία για εκδηλώσεις, φεστιβάλ και πάρτι.')">
    <meta property="og:title" content="XD Cinema · 5D Cinema Προσομοιωτής">
    <meta property="og:description" content="Ζήσε την απόλυτη εμπειρία! Κινητός 5D προσομοιωτής για εκδηλώσεις.">
    <meta property="og:image" content="{{ asset('img/poster-lid.jpg') }}">
    <meta property="og:type" content="website">
    <meta name="theme-color" content="#07090d">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <script>window.XD = { img: @json(asset('img')) };</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grain antialiased">

{{-- SVG φίλτρα για το εφέ σπρέι --}}
<svg width="0" height="0" class="absolute" aria-hidden="true">
    <defs>
        <filter id="spray-edge" x="-5%" y="-5%" width="110%" height="110%">
            <feTurbulence type="fractalNoise" baseFrequency="0.95" numOctaves="2" seed="7" result="noise"/>
            <feDisplacementMap in="SourceGraphic" in2="noise" scale="3.5" xChannelSelector="R" yChannelSelector="G"/>
        </filter>
        <filter id="rough" x="-5%" y="-5%" width="110%" height="110%">
            <feTurbulence type="fractalNoise" baseFrequency="0.06" numOctaves="3" seed="3" result="noise"/>
            <feDisplacementMap in="SourceGraphic" in2="noise" scale="9"/>
        </filter>
    </defs>
</svg>

@include('partials.nav')

<main>
    @yield('content')
</main>

@include('partials.footer')

{{-- Επίδειξη εφέ σε όλη την οθόνη --}}
<canvas id="fx-stage" aria-hidden="true"></canvas>
<div id="fx-flash" aria-hidden="true"></div>
<div id="fx-label" aria-live="polite"></div>

{{-- Lightbox γκαλερί --}}
<div id="lightbox" class="hidden" role="dialog" aria-modal="true" aria-label="Προβολή εικόνας">
    <img src="" alt="">
    <p class="lb-cap"></p>
    <button type="button" class="lb-close" aria-label="Κλείσιμο">✕</button>
    <button type="button" class="lb-prev" aria-label="Προηγούμενη">‹</button>
    <button type="button" class="lb-next" aria-label="Επόμενη">›</button>
</div>

{{-- Sticky κουμπί στο κινητό --}}
<div id="mobile-cta">
    <a href="#contact" class="btn-neon flex-1 justify-center !py-2.5 text-base">Κλείσε θέση</a>
    <a href="#movies" class="btn-ghost justify-center !py-2.5 text-base">Ταινίες</a>
</div>

{{-- Δομημένα δεδομένα --}}
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "EntertainmentBusiness",
    "name": "XD Cinema",
    "alternateName": "XD Cinema 5D",
    "url": "https://www.xdcinema.gr",
    "email": "info@xdcinema.gr",
    "description": "Κινητός 5D cinema προσομοιωτής με κινούμενα καθίσματα και ειδικά εφέ (αέρας, νερό, χιόνι, φυσαλίδες, καπνός) για εκδηλώσεις, φεστιβάλ, σχολεία και πάρτι.",
    "image": "{{ asset('img/gen/logo-wall.jpg') }}",
    "areaServed": "GR",
    "knowsLanguage": ["el", "en"]
}
</script>

</body>
</html>
