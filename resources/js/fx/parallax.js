import gsap from 'gsap';

// Parallax με το ποντίκι: κάθε layer έχει data-depth (0 = ακίνητο, 1 = μέγιστη κίνηση)
export function initMouseParallax(host, selector = '[data-depth]') {
    if (!host || matchMedia('(hover: none)').matches) return;
    const layers = [...host.querySelectorAll(selector)];
    if (!layers.length) return;
    const setters = layers.map((el) => ({
        depth: parseFloat(el.dataset.depth || '0'),
        x: gsap.quickTo(el, 'x', { duration: 0.9, ease: 'power3.out' }),
        y: gsap.quickTo(el, 'y', { duration: 0.9, ease: 'power3.out' }),
        rx: gsap.quickTo(el, 'rotateX', { duration: 1.2, ease: 'power3.out' }),
        ry: gsap.quickTo(el, 'rotateY', { duration: 1.2, ease: 'power3.out' }),
    }));
    host.addEventListener('pointermove', (e) => {
        const r = host.getBoundingClientRect();
        const nx = (e.clientX - r.left) / r.width - 0.5;
        const ny = (e.clientY - r.top) / r.height - 0.5;
        for (const s of setters) {
            s.x(-nx * 60 * s.depth); s.y(-ny * 40 * s.depth);
            s.ry(nx * 6 * s.depth); s.rx(-ny * 6 * s.depth);
        }
    }, { passive: true });
    host.addEventListener('pointerleave', () => setters.forEach((s) => { s.x(0); s.y(0); s.rx(0); s.ry(0); }));
}

// Κλίση κάρτας στο hover (3D tilt)
export function initTilt(selector = '[data-tilt]') {
    if (matchMedia('(hover: none)').matches) return;
    document.querySelectorAll(selector).forEach((card) => {
        const rx = gsap.quickTo(card, 'rotateX', { duration: 0.5, ease: 'power2.out' });
        const ry = gsap.quickTo(card, 'rotateY', { duration: 0.5, ease: 'power2.out' });
        card.style.transformPerspective = '900px';
        card.addEventListener('pointermove', (e) => {
            const r = card.getBoundingClientRect();
            ry(((e.clientX - r.left) / r.width - 0.5) * 16);
            rx(-((e.clientY - r.top) / r.height - 0.5) * 16);
        });
        card.addEventListener('pointerleave', () => { rx(0); ry(0); });
    });
}
