import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';
import { initRain } from './fx/rain';
import { initLightning } from './fx/lightning';
import { initEmbers } from './fx/embers';
import { initTilt } from './fx/parallax';
import { initFxDemo } from './fx/demo';
import { initLightbox } from './fx/lightbox';

gsap.registerPlugin(ScrollTrigger);
document.documentElement.classList.remove('no-js');

const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
const $ = (s, c = document) => c.querySelector(s);
const $$ = (s, c = document) => [...c.querySelectorAll(s)];
const img = (name) => `${window.XD?.img || '/img'}/${name}`;

/* ---------- Ομαλό scroll (Lenis) + ScrollTrigger ---------- */
let lenis = null;
if (!reduce) {
    lenis = new Lenis({ lerp: 0.1, smoothWheel: true });
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((t) => lenis.raf(t * 1000));
    gsap.ticker.lagSmoothing(0);
}
const scrollTo = (target) => (lenis ? lenis.scrollTo(target, { offset: -64 }) : target.scrollIntoView({ behavior: 'smooth' }));
$$('a[href^="#"]').forEach((a) => a.addEventListener('click', (e) => {
    const t = $(a.getAttribute('href'));
    if (t) { e.preventDefault(); scrollTo(t); closeMenu(); }
}));

/* ---------- Μενού ---------- */
const nav = $('#nav'), burger = $('#burger'), menu = $('#mobile-menu');
const closeMenu = () => { menu?.classList.add('hidden'); burger?.setAttribute('aria-expanded', 'false'); };
burger?.addEventListener('click', () => {
    const open = menu.classList.toggle('hidden') === false;
    burger.setAttribute('aria-expanded', String(open));
});
ScrollTrigger.create({ start: 'top -80', onUpdate: (self) => nav?.classList.toggle('is-scrolled', self.scroll() > 80) });

/* ---------- Preloader + είσοδος ---------- */
const pre = $('#preloader');
const intro = gsap.timeline({ defaults: { ease: 'power3.out' } });
if (pre) {
    intro.to('#preloader .pre-xd', { scale: 1, opacity: 1, duration: 0.5, ease: 'back.out(2)' })
         .to('#preloader .pre-bar', { scaleX: 1, duration: 0.7, ease: 'power2.inOut' }, '-=0.1')
         .to(pre, { yPercent: -100, duration: 0.7, ease: 'power4.inOut' })
         .set(pre, { display: 'none' });
}
intro.from('.hero-title > *', { y: 60, opacity: 0, rotateX: -40, duration: 0.8, stagger: 0.12, ease: 'back.out(1.6)' }, '-=0.4')
     .from('.hero-cta > *', { y: 30, opacity: 0, duration: 0.6, stagger: 0.1 }, '-=0.4')
     .from('#scroll-hint', { opacity: 0, y: -10, duration: 0.6 }, '-=0.2');

/* ---------- Το 3D ταξίδι ---------- */
const journey = $('#journey');
if (journey) {
    const stage = $('.journey-stage', journey);
    let journeyState = { pSmooth: 0 };
    if (!reduce) {
        initRain($('#rain'));
        // αστραπές μόνο στην καταιγίδα (πρώτο 30% του ταξιδιού)
        initLightning({ flash: $('.hero-flash'), bolt: $('.hero-bolt'), host: stage, active: () => journeyState.pSmooth < 0.2 });
    }
    // Σταθμοί: εικόνες ως επίπεδα σε βάθος. w/h σε μονάδες σκηνής, dz = επιπλέον βάθος, x/y θέση.
    // Το Three.js φορτώνει ξεχωριστά (code-splitting) για να μην καθυστερεί το υπόλοιπο
    import('./fx/journey').then(({ initJourney }) => {
    const api = initJourney(journey, [
        { layers: [ // 0: τσουνάμι στην πόλη (hero)
            { src: img('gen/hero-tsunami.jpg'), w: 46, h: 25.7, dz: 14, y: 1.5, soft: false, opacity: 0.95 },
            { src: img('layer-ship.jpg'), w: 4.2, h: 5.4, x: -1.2, y: 1.6, dz: 7, ry: 0.15, opacity: 0.85 },
            { src: img('layer-pirate.jpg'), w: 6.2, h: 9.9, x: -6.3, y: -1.6, dz: 1, ry: 0.25 },
            { src: img('layer-kong.jpg'), w: 7.2, h: 9, x: 6.6, y: -1, dz: 2, ry: -0.25 },
        ] },
        { layers: [ // 1: η κατάρα του πειρατή (σεντούκι)
            { src: img('gen/scene-treasure.jpg'), w: 7.6, h: 10.2, x: -3.4, y: -0.4, dz: 0, ry: 0.18 },
            { src: img('layer-pirate.jpg'), w: 5.6, h: 8.9, x: 4.6, y: -1.2, dz: 3, ry: -0.22 },
            { src: img('layer-sky.jpg'), w: 40, h: 9.5, y: 7, dz: 12, soft: false, opacity: 0.8 },
        ] },
        { layers: [ // 2: καρχαρίας
            { src: img('gen/scene-shark.jpg'), w: 8.6, h: 11.5, x: 0.4, y: 0.2, dz: 0, ry: -0.08 },
            { src: img('backdrop-right.jpg'), w: 36, h: 14.6, y: 1, dz: 13, soft: false, opacity: 0.5 },
        ] },
        { layers: [ // 3: Κονγκ στην πόλη
            { src: img('gen/scene-kong-city.jpg'), w: 8.6, h: 11.5, x: 2.8, y: 0, dz: 0, ry: -0.2 },
            { src: img('layer-coaster.jpg'), w: 7, h: 7.6, x: -6.8, y: 1.5, dz: 7, ry: 0.3, opacity: 0.8 },
        ] },
        { layers: [ // 4: πόλη θρίλερ
            { src: img('gen/scene-city.jpg'), w: 8.4, h: 11.2, x: -2.4, y: 0.2, dz: 0, ry: 0.16 },
            { src: img('gen/scene-shark.jpg'), w: 5, h: 6.7, x: 7, y: 1.8, dz: 7, ry: -0.35, opacity: 0.7 },
        ] },
        { bricks: true, layers: [ // 5: T-Rex σπάει τον τοίχο με graffiti
            { src: img('gen/trex-graffiti-wall.jpg'), w: 15.4, h: 8.6, y: 0.2, dz: 0.4, soft: false },
        ] },
        { layers: [ // 6: roller coaster
            { src: img('layer-coaster.jpg'), w: 11, h: 12, x: 0.5, y: -0.2, dz: 0, ry: 0.1 },
            { src: img('layer-trex-jungle.jpg'), w: 8, h: 7.1, x: -7.5, y: 2, dz: 6, ry: 0.35 },
            { src: img('gen/scene-kong-city.jpg'), w: 5, h: 6.7, x: 7.6, y: 2.2, dz: 7, ry: -0.35, opacity: 0.75 },
        ] },
        { layers: [ // 7: κλείσιμο / CTA
            { src: img('gen/logo-wall.jpg'), w: 24, h: 13.1, y: 0.6, dz: 4, soft: false, opacity: 0.5 },
        ] },
    ], { reduce });
    if (api) journeyState = api.state;
    });

    // η βροχή σβήνει όσο φεύγουμε από την καταιγίδα
    gsap.to('#rain', { opacity: 0, ease: 'none', scrollTrigger: { trigger: journey, start: 'top top', end: '22% top', scrub: true } });
}

/* ---------- Αποκάλυψη στοιχείων στο scroll ---------- */
if (!reduce) {
    ScrollTrigger.batch('[data-reveal]', {
        start: 'top 88%',
        onEnter: (els) => gsap.to(els, { opacity: 1, y: 0, duration: 0.9, stagger: 0.1, ease: 'power3.out' }),
    });
} else {
    gsap.set('[data-reveal]', { opacity: 1, y: 0 });
}

/* ---------- Ο T-Rex στον τοίχο (ενότητα εμπειρίας) ---------- */
const wall = $('#wall');
if (wall && !reduce) {
    gsap.fromTo('#trex', { scale: 0.6, rotate: -5, opacity: 0.5 },
        { scale: 1, rotate: 0, opacity: 1, ease: 'power2.out',
          scrollTrigger: { trigger: wall, start: 'top 80%', end: 'center 45%', scrub: 0.6 } });
    ScrollTrigger.create({
        trigger: wall, start: 'center 60%', once: true,
        onEnter: () => gsap.fromTo('#wall .shake', { x: -6 }, { x: 6, duration: 0.05, repeat: 9, yoyo: true, clearProps: 'x' }),
    });
}

/* ---------- Σπίθες στην ενότητα των εφέ ---------- */
if (!reduce) initEmbers($('#embers'));

/* ---------- Μετρητές ---------- */
$$('[data-count]').forEach((el) => {
    const end = parseFloat(el.dataset.count);
    const obj = { v: 0 };
    ScrollTrigger.create({ trigger: el, start: 'top 85%', once: true, onEnter: () =>
        gsap.to(obj, { v: end, duration: 1.8, ease: 'power2.out', onUpdate: () => { el.textContent = Math.round(obj.v).toLocaleString('el-GR'); } }) });
});

/* ---------- Κάρτες με κλίση ---------- */
if (!reduce) initTilt('[data-tilt]');

/* ---------- Επίδειξη εφέ σε όλη την οθόνη ---------- */
const playFx = reduce ? () => {} : initFxDemo({ canvas: $('#fx-stage'), flash: $('#fx-flash'), shakeTarget: $('main'), label: $('#fx-label') });
$$('[data-fx]').forEach((el) => el.addEventListener('click', () => playFx(el.dataset.fx)));

/* ---------- Γκαλερί ---------- */
initLightbox($('#lightbox'));

/* ---------- Sticky κουμπί στο κινητό: κρύβεται στην επικοινωνία ---------- */
const bar = $('#mobile-cta');
if (bar) ScrollTrigger.create({ trigger: '#contact', start: 'top 80%', end: 'bottom top', onToggle: (st) => bar.classList.toggle('is-hidden', st.isActive) });

/* ---------- FAQ: ένα ανοιχτό κάθε φορά ---------- */
$$('.faq details').forEach((d) => d.addEventListener('toggle', () => { if (d.open) $$('.faq details').forEach((o) => { if (o !== d) o.open = false; }); }));

/* ---------- Φόρμα: κλείσιμο μηνύματος ---------- */
$('#flash-close')?.addEventListener('click', () => $('#flash')?.remove());

window.addEventListener('load', () => ScrollTrigger.refresh());
