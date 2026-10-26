import gsap from 'gsap';

/*
 * Διαδραστική επίδειξη εφέ σε όλη την οθόνη: χιόνι, νερό, φυσαλίδες, καπνός, άνεμος, αστραπή, τράνταγμα, δόνηση.
 * Καλείται με το όνομα του εφέ. Σταματά μόνο του όταν τελειώσουν τα σωματίδια.
 */
export function initFxDemo({ canvas, flash, shakeTarget, label }) {
    if (!canvas) return () => {};
    const ctx = canvas.getContext('2d');
    let w = 0, h = 0, dpr = 1, parts = [], raf = 0, running = false, emitUntil = 0, emitType = null;
    const smokeTex = makeSoft('rgba(190,196,205,1)');
    const dropTex = makeSoft('rgba(200,230,255,1)');

    const resize = () => {
        dpr = Math.min(window.devicePixelRatio || 1, 2);
        w = window.innerWidth; h = window.innerHeight;
        canvas.width = w * dpr; canvas.height = h * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    };
    window.addEventListener('resize', resize, { passive: true });
    resize();

    const R = (a, b) => a + Math.random() * (b - a);

    const spawners = {
        snow: () => ({ t: 'snow', x: R(-20, w + 20), y: R(-40, -5), r: R(1.2, 4), vy: R(0.6, 1.8), vx: R(-0.4, 0.4), ph: R(0, 6.28), a: R(0.5, 0.95) }),
        water: () => (Math.random() < 0.12
            ? { t: 'lens', x: R(0, w), y: R(0, h * 0.6), r: R(10, 26), vy: R(0.15, 0.5), a: 0.0, life: 1, grow: 1 }
            : { t: 'drop', x: R(w * 0.2, w * 0.8), y: R(-30, -5), vx: R(-3, 3), vy: R(6, 12), len: R(10, 24), a: R(0.5, 0.9) }),
        bubbles: () => ({ t: 'bubble', x: R(0, w), y: h + R(5, 60), r: R(4, 18), vy: R(0.8, 2.2), ph: R(0, 6.28), a: R(0.5, 0.9) }),
        smoke: () => ({ t: 'smoke', x: R(-50, w + 50), y: h + R(20, 120), r: R(60, 160), vy: R(0.3, 0.9), vx: R(-0.4, 0.4), a: 0, life: 1, rot: R(0, 6.28) }),
        wind: () => ({ t: 'streak', x: w + R(0, 200), y: R(0, h), vx: -R(14, 30), len: R(60, 220), a: R(0.15, 0.5), th: R(0.6, 1.6) }),
        sparks: () => ({ t: 'spark', x: R(0, w), y: R(0, h * 0.5), vx: R(-2, 2), vy: R(1, 4), r: R(1, 2.5), a: 1, life: 1 }),
    };

    const emit = (type, perFrame, ms) => { emitType = type; emitUntil = performance.now() + ms; emitRate = perFrame; start(); };
    let emitRate = 0;

    const tick = () => {
        if (!running) return;
        const now = performance.now();
        if (emitType && now < emitUntil) {
            for (let i = 0; i < emitRate; i++) parts.push(spawners[emitType]());
        }
        ctx.clearRect(0, 0, w, h);
        const next = [];
        for (const p of parts) {
            switch (p.t) {
                case 'snow':
                    p.ph += 0.02; p.x += p.vx + Math.sin(p.ph) * 0.6; p.y += p.vy;
                    ctx.beginPath(); ctx.fillStyle = `rgba(255,255,255,${p.a})`; ctx.arc(p.x, p.y, p.r, 0, 6.28); ctx.fill();
                    if (p.y < h + 10) next.push(p); break;
                case 'drop':
                    p.vy += 0.35; p.x += p.vx; p.y += p.vy;
                    ctx.strokeStyle = `rgba(190,225,255,${p.a})`; ctx.lineWidth = 2; ctx.lineCap = 'round';
                    ctx.beginPath(); ctx.moveTo(p.x, p.y); ctx.lineTo(p.x - p.vx * 0.6, p.y - p.len); ctx.stroke();
                    if (p.y < h + 30) next.push(p); break;
                case 'lens': // σταγόνες πάνω στον «φακό»
                    p.a = Math.min(0.85, p.a + 0.04); p.y += p.vy; p.life -= 0.004;
                    ctx.save(); ctx.globalAlpha = p.a * Math.max(0, p.life);
                    const g = ctx.createRadialGradient(p.x - p.r * 0.3, p.y - p.r * 0.3, p.r * 0.1, p.x, p.y, p.r);
                    g.addColorStop(0, 'rgba(255,255,255,.9)'); g.addColorStop(0.5, 'rgba(180,215,255,.35)'); g.addColorStop(1, 'rgba(120,170,230,.08)');
                    ctx.fillStyle = g; ctx.beginPath(); ctx.ellipse(p.x, p.y, p.r * 0.8, p.r, 0, 0, 6.28); ctx.fill();
                    ctx.strokeStyle = 'rgba(255,255,255,.35)'; ctx.lineWidth = 1; ctx.stroke(); ctx.restore();
                    if (p.life > 0 && p.y < h + 40) next.push(p); break;
                case 'bubble':
                    p.ph += 0.04; p.x += Math.sin(p.ph) * 0.8; p.y -= p.vy;
                    ctx.save(); ctx.globalAlpha = p.a;
                    ctx.strokeStyle = 'rgba(200,240,255,.9)'; ctx.lineWidth = 1.5; ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, 6.28); ctx.stroke();
                    ctx.fillStyle = 'rgba(140,200,255,.12)'; ctx.fill();
                    ctx.fillStyle = 'rgba(255,255,255,.9)'; ctx.beginPath(); ctx.arc(p.x - p.r * 0.35, p.y - p.r * 0.35, p.r * 0.2, 0, 6.28); ctx.fill();
                    ctx.restore();
                    if (p.y > -30) next.push(p); break;
                case 'smoke':
                    p.a = Math.min(0.55, p.a + 0.01); p.life -= 0.0035; p.y -= p.vy; p.x += p.vx; p.r += 0.5; p.rot += 0.002;
                    ctx.save(); ctx.globalAlpha = p.a * Math.max(0, p.life); ctx.translate(p.x, p.y); ctx.rotate(p.rot);
                    ctx.drawImage(smokeTex, -p.r, -p.r, p.r * 2, p.r * 2); ctx.restore();
                    if (p.life > 0) next.push(p); break;
                case 'streak':
                    p.x += p.vx;
                    ctx.strokeStyle = `rgba(255,255,255,${p.a})`; ctx.lineWidth = p.th; ctx.lineCap = 'round';
                    ctx.beginPath(); ctx.moveTo(p.x, p.y); ctx.lineTo(p.x + p.len, p.y + 2); ctx.stroke();
                    if (p.x + p.len > -10) next.push(p); break;
                case 'spark':
                    p.vy += 0.08; p.x += p.vx; p.y += p.vy; p.life -= 0.02;
                    ctx.save(); ctx.globalAlpha = Math.max(0, p.life); ctx.drawImage(dropTex, p.x - p.r * 3, p.y - p.r * 3, p.r * 6, p.r * 6); ctx.restore();
                    if (p.life > 0) next.push(p); break;
            }
        }
        parts = next;
        if (parts.length || now < emitUntil) raf = requestAnimationFrame(tick);
        else { running = false; ctx.clearRect(0, 0, w, h); canvas.classList.remove('is-on'); }
    };
    const start = () => { if (!running) { running = true; canvas.classList.add('is-on'); tick(); } };

    const say = (text) => {
        if (!label) return;
        label.textContent = text;
        gsap.killTweensOf(label);
        gsap.fromTo(label, { opacity: 0, y: 20, scale: 0.9 }, { opacity: 1, y: 0, scale: 1, duration: 0.35, ease: 'back.out(2)' });
        gsap.to(label, { opacity: 0, y: -10, duration: 0.4, delay: 1.6 });
    };
    const shake = (amp = 8, times = 10) => {
        if (!shakeTarget) return;
        gsap.fromTo(shakeTarget, { x: -amp, y: amp * 0.4 }, { x: amp, y: -amp * 0.4, duration: 0.05, repeat: times, yoyo: true, clearProps: 'x,y' });
    };

    return (fx) => {
        switch (fx) {
            case 'snow': say('Χιόνι ❄'); emit('snow', 4, 3500); break;
            case 'water': say('Νερό 💦'); emit('water', 6, 1800); break;
            case 'bubbles': say('Φυσαλίδες'); emit('bubbles', 2, 3000); break;
            case 'smoke': say('Καπνός'); emit('smoke', 1, 2800); break;
            case 'wind': say('Αέρας'); emit('wind', 3, 2200); gsap.fromTo(shakeTarget, { x: 0 }, { x: -6, duration: 0.3, yoyo: true, repeat: 5, clearProps: 'x' }); break;
            case 'lightning':
                say('Αστραπή ⚡'); emit('sparks', 8, 400);
                if (flash) gsap.timeline().set(flash, { opacity: 0.8 }).to(flash, { opacity: 0.1, duration: 0.08 }).to(flash, { opacity: 0.6, duration: 0.05 }).to(flash, { opacity: 0, duration: 0.5 });
                break;
            case 'shake': say('Κίνηση'); shake(10, 12); emit('sparks', 3, 300); break;
            case 'vibrate': say('Δόνηση'); shake(3, 24); if (navigator.vibrate) navigator.vibrate([90, 40, 90, 40, 160]); break;
        }
    };
}

function makeSoft(color) {
    const c = document.createElement('canvas'); c.width = c.height = 128;
    const g = c.getContext('2d');
    const grad = g.createRadialGradient(64, 64, 0, 64, 64, 64);
    grad.addColorStop(0, color); grad.addColorStop(0.45, color.replace(',1)', ',.45)')); grad.addColorStop(1, color.replace(',1)', ',0)'));
    g.fillStyle = grad; g.fillRect(0, 0, 128, 128);
    return c;
}
