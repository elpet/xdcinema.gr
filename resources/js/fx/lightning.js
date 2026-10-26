import gsap from 'gsap';

// Αστραπές: λευκό flash + τυχαίος κεραυνός (SVG path) κάθε 4-11 δευτερόλεπτα
export function initLightning({ flash, bolt, host, active = () => true }) {
    if (!flash || !bolt || !host) return;
    const path = bolt.querySelector('path');
    let alive = true;

    const makeBolt = () => {
        const W = host.clientWidth, H = host.clientHeight;
        let x = W * (0.15 + Math.random() * 0.7), y = -10;
        const pts = [`M${x} ${y}`];
        const endY = H * (0.35 + Math.random() * 0.3);
        while (y < endY) {
            x += (Math.random() - 0.5) * 70;
            y += 18 + Math.random() * 40;
            pts.push(`L${x} ${y}`);
            if (Math.random() < 0.18) { // διακλάδωση
                pts.push(`M${x} ${y} l${(Math.random() - 0.5) * 120} ${30 + Math.random() * 90} M${x} ${y}`);
            }
        }
        bolt.setAttribute('viewBox', `0 0 ${W} ${H}`);
        path.setAttribute('d', pts.join(' '));
        const len = path.getTotalLength();
        path.style.strokeDasharray = len;
        path.style.strokeDashoffset = len;
    };

    const strike = () => {
        if (!alive) return;
        if (!active()) { schedule(); return; } // π.χ. μόνο όσο είμαστε στην καταιγίδα
        makeBolt();
        const tl = gsap.timeline({ onComplete: schedule });
        tl.set(bolt, { opacity: 1 })
          .to(path, { strokeDashoffset: 0, duration: 0.12, ease: 'power1.in' })
          .to(flash, { opacity: 0.55, duration: 0.05 }, '<')
          .to(flash, { opacity: 0.15, duration: 0.08 })
          .to(flash, { opacity: 0.35, duration: 0.05 })
          .to(flash, { opacity: 0, duration: 0.35, ease: 'power2.out' })
          .to(bolt, { opacity: 0, duration: 0.25 }, '-=0.3');
    };

    const schedule = () => {
        if (!alive) return;
        gsap.delayedCall(4 + Math.random() * 7, strike);
    };

    // Μόνο όταν το hero φαίνεται
    new IntersectionObserver(([e]) => {
        if (e.isIntersecting && !alive) { alive = true; schedule(); }
        if (!e.isIntersecting) { alive = false; gsap.killTweensOf(flash); gsap.set([flash, bolt], { opacity: 0 }); }
    }, { threshold: 0.05 }).observe(host);

    gsap.delayedCall(1.8, strike);
}
