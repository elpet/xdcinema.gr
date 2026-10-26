// Βροχή σε canvas (ελαφριά, σταματά όταν το hero δεν φαίνεται)
export function initRain(canvas, { count = 220 } = {}) {
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let w = 0, h = 0, drops = [], running = false, raf = 0;

    const resize = () => {
        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        w = canvas.clientWidth; h = canvas.clientHeight;
        canvas.width = w * dpr; canvas.height = h * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        drops = Array.from({ length: Math.round(count * (w / 1400)) + 40 }, spawn);
    };
    const spawn = () => ({
        x: Math.random() * (w + 200) - 100,
        y: Math.random() * -h,
        len: 12 + Math.random() * 22,
        speed: 9 + Math.random() * 9,
        drift: 1.4 + Math.random() * 1.2,
        alpha: .12 + Math.random() * .25,
    });

    const tick = () => {
        if (!running) return;
        ctx.clearRect(0, 0, w, h);
        ctx.lineWidth = 1.1;
        ctx.lineCap = 'round';
        for (const d of drops) {
            ctx.strokeStyle = `rgba(200,220,255,${d.alpha})`;
            ctx.beginPath();
            ctx.moveTo(d.x, d.y);
            ctx.lineTo(d.x - d.drift * 1.6, d.y + d.len);
            ctx.stroke();
            d.x -= d.drift; d.y += d.speed;
            if (d.y > h + 30) { Object.assign(d, spawn(), { y: -40 }); }
        }
        raf = requestAnimationFrame(tick);
    };

    const start = () => { if (!running) { running = true; tick(); } };
    const stop = () => { running = false; cancelAnimationFrame(raf); };

    resize();
    window.addEventListener('resize', resize, { passive: true });
    new IntersectionObserver(([e]) => (e.isIntersecting ? start() : stop()), { threshold: 0.02 }).observe(canvas);
}
