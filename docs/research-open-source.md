# Έρευνα open-source πόρων για το xdcinema.gr (26/10/2026)

Συμπέρασμα: δεν υπάρχει έτοιμο cinema/graffiti theme στο GitHub. Το site χτίζεται custom (Laravel 12 + Vite + Tailwind 4 + GSAP + Lenis) και δανείζεται μοτίβα από τα παρακάτω. Όλα MIT εκτός αν σημειώνεται.

## Templates για «δανεισμό» μοτίβων
| Repo | Τι παίρνουμε |
|---|---|
| https://github.com/MiddleSugar7000/volt-arc-landing-page-template | preloader, pinned ScrollTrigger σκηνές, Lenis+GSAP σύνδεση, counters |
| https://github.com/MiddleSugar7000/lumen-saas-landing-page-template | Three.js σωματίδια που μεταμορφώνονται στο scroll, SplitText reveals, marquee, FAQ/pricing |
| https://github.com/8bsite/event-website-template | δομή event site (lineup → πρόγραμμα → εισιτήρια → FAQ → επικοινωνία), κάρτες εισιτηρίων «βεντάλια», οριζόντια γκαλερί |
| https://github.com/AleBrito124356/threejs-hero-collection | έτοιμα Three.js hero backgrounds (Waves για τσουνάμι, Aurora) |
| https://github.com/codrops/* (OnScrollTypographyAnimations, ScrollBasedLayoutAnimations, Scroll3DGrid, ImageToContent, ImagePixelLoading) | μεμονωμένα εφέ τυπογραφίας/εικόνων |

Απορρίφθηκαν: GTA VI / Zentry clones (χωρίς άδεια ή μόνο εκπαιδευτικά), rainyday.js (GPL), canvas-ui (Commons Clause).

## Βιβλιοθήκες εφέ (δωρεάν)
- **GSAP** (ScrollTrigger, SplitText, ScrambleText, DrawSVG, MorphSVG, Flip): δωρεάν και για εμπορική χρήση από το 2025. Άδεια «GSAP Standard» (όχι MIT). Έλεγχος κειμένου άδειας πριν το launch: https://gsap.com/standard-license/
- **Lenis** smooth scroll · **Three.js** · **tsParticles** (φωτιά, σπίθες, φυσαλίδες, χιόνι) · **Vanta.js** (ομίχλη/κύματα/σύννεφα)
- **PowerGlitch** (glitch) · **VFX-JS** (WebGL shaders σε εικόνες) · **pixi-filters** (ShockwaveFilter για το «σπάσιμο τοίχου», OldFilm/CRT)
- **WebGL-Fluid-Simulation** (καπνός/νερό διαδραστικό) · **three-nebula** (3D σωματίδια) · **hover-effect** / **curtains.js** (distortion εικόνων)
- **p5.brush** / **Atrament** (σπρέι/ζωγραφική σε canvas) · **fluid-marquee** / **Marquee3000** · **canvas-confetti** (ISC)
- Κεραυνός: `LightningStrike` από three-stdlib ή custom canvas (υλοποιήθηκε custom στο `resources/js/fx/lightning.js`). Βροχή: custom canvas (`fx/rain.js`).

## Γραμματοσειρές (OFL/Apache, Google Fonts)
Rubik Spray Paint, Rubik Wet Paint, Rubik Glitch, Rubik Burned, Rubik Dirt, Sedgwick Ave Display, Bangers, Bungee, Black Ops One, Anton, Permanent Marker, Rock Salt, Nosifer, Creepster.
Προσοχή: οι περισσότερες **δεν έχουν ελληνικά**. Για ελληνικούς τίτλους χρησιμοποιούμε Sofia Sans Extra Condensed 900 (έχει ελληνικά), για body Inter. Οι graffiti γραμματοσειρές μόνο για λατινικά (XD CINEMA, 5D, ENTER).

## Υφές/εικόνες CC0 (χωρίς αναφορά)
- ambientCG: Graffiti Set 001 (decals με alpha) https://ambientcg.com/view?id=GraffitiSet001 · Painted Bricks 001 · Concrete 034 · Bricks105+
- Poly Haven: worn_brick_wall, brick_wall_001, concrete_wall_007, painted_concrete
- Kenney: Particle Pack (καπνός, φλόγες, σπίθες) https://kenney.nl/assets/particle-pack · Smoke Particles
- OpenGameArt (CC0): lightning frames (node/38190), 100 grunge brushstrokes & splatters, explosions, water splash
- Matriax Free CG Textures (CC0, grunge/concrete/asphalt)
- Pexels / Pixabay (δωρεάν εμπορική χρήση, χωρίς αναφορά): graffiti wall, lightning png, water splash png, smoke png
- Openclipart (CC0): splats, drips, skulls, pirate icons
