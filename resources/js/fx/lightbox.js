// Απλό lightbox για τη γκαλερί: κλικ σε [data-lightbox], βέλη, Esc, κλικ έξω
export function initLightbox(root) {
    if (!root) return;
    const img = root.querySelector('img'), cap = root.querySelector('.lb-cap');
    const items = [...document.querySelectorAll('[data-lightbox]')];
    let i = 0;
    const show = (n) => {
        i = (n + items.length) % items.length;
        const el = items[i];
        img.src = el.dataset.lightbox; img.alt = el.querySelector('img')?.alt || '';
        if (cap) cap.textContent = el.dataset.caption || img.alt;
    };
    const open = (n) => { show(n); root.classList.remove('hidden'); document.documentElement.classList.add('lenis-stopped'); root.querySelector('.lb-close')?.focus(); };
    const close = () => { root.classList.add('hidden'); document.documentElement.classList.remove('lenis-stopped'); };
    items.forEach((el, n) => el.addEventListener('click', (e) => { e.preventDefault(); open(n); }));
    root.querySelector('.lb-close')?.addEventListener('click', close);
    root.querySelector('.lb-prev')?.addEventListener('click', () => show(i - 1));
    root.querySelector('.lb-next')?.addEventListener('click', () => show(i + 1));
    root.addEventListener('click', (e) => { if (e.target === root) close(); });
    document.addEventListener('keydown', (e) => {
        if (root.classList.contains('hidden')) return;
        if (e.key === 'Escape') close();
        if (e.key === 'ArrowLeft') show(i - 1);
        if (e.key === 'ArrowRight') show(i + 1);
    });
}
