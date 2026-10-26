// Σπίθες/κάρβουνα που ανεβαίνουν (για τις σκηνές με φωτιά)
export function initEmbers(canvas, { count = 90 } = {}) {
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let w = 0, h = 0, p = [], running = false, raf = 0;
    const spawn = () => ({
        x: Math.random() * w, y: h + Math.random() * 60,
        r: 1 + Math.random() * 2.4,
        vy: 0.5 + Math.random() * 1.4, vx: (Math.random() - 0.5) * 0.6,
        life: 0.4 + Math.random() * 0.6, hue: 20 + Math.random() * 30,
        wob: Math.random() * Math.PI * 2,
    });
    const resize = () => {
        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        w = canvas.clientWidth; h = canvas.clientHeight;
        canvas.width = w * dpr; canvas.height = h * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        p = Array.from({ length: count }, () => ({ ...spawn(), y: Math.random() * h }));
    };
    const tick = () => {
        if (!running) return;
        ctx.clearRect(0, 0, w, h);
        for (const e of p) {
            e.wob += 0.03; e.x += e.vx + Math.sin(e.wob) * 0.4; e.y -= e.vy; e.life -= 0.003;
            if (e.life <= 0 || e.y < -10) Object.assign(e, spawn());
            ctx.beginPath();
            ctx.fillStyle = `hsla(${e.hue},100%,60%,${Math.min(1, e.life)})`;
            ctx.shadowBlur = 10; ctx.shadowColor = `hsla(${e.hue},100%,55%,.9)`;
            ctx.arc(e.x, e.y, e.r, 0, Math.PI * 2); ctx.fill();
        }
        raf = requestAnimationFrame(tick);
    };
    const start = () => { if (!running) { running = true; tick(); } };
    const stop = () => { running = false; cancelAnimationFrame(raf); };
    resize();
    window.addEventListener('resize', resize, { passive: true });
    new IntersectionObserver(([e]) => (e.isIntersecting ? start() : stop()), { threshold: 0.02 }).observe(canvas);
}
