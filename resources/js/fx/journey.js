import * as THREE from 'three';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

/*
 * Το 3D «ταξίδι»: η κάμερα κινείται στον άξονα Z μέσα από σταθμούς (σκηνές) καθώς ο χρήστης κάνει scroll.
 * Κάθε σταθμός έχει επίπεδα (planes) με εικόνες σε διαφορετικό βάθος, ώστε να υπάρχει parallax.
 * Σωματίδια (σπίθες/σκόνη) και ομίχλη ενώνουν τις σκηνές. Το ποντίκι μετακινεί ελαφρά την κάμερα.
 */
export function initJourney(root, stations, { spacing = 18, reduce = false } = {}) {
    const stage = root.querySelector('.journey-stage');
    const canvas = root.querySelector('canvas.journey-canvas');
    const captions = [...root.querySelectorAll('[data-station]')];
    if (!stage || !canvas || !stations.length) return;

    let renderer;
    try {
        renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true, powerPreference: 'high-performance' });
    } catch {
        root.classList.add('no-webgl');
        return;
    }
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 1.5));
    renderer.setClearColor(0x000000, 0);

    const scene = new THREE.Scene();
    scene.fog = new THREE.FogExp2(0x07090d, 0.05);
    const camera = new THREE.PerspectiveCamera(55, 1, 0.1, 300);

    /* ---------- Υφές ---------- */
    const loader = new THREE.TextureLoader();
    const vignette = makeVignetteTexture();
    const tex = (src) => {
        const t = loader.load(src);
        t.colorSpace = THREE.SRGBColorSpace;
        t.anisotropy = Math.min(4, renderer.capabilities.getMaxAnisotropy());
        return t;
    };

    // Σε κάθετες οθόνες (κινητά) τα επίπεδα μικραίνουν και πλησιάζουν στο κέντρο
    const k = stage.clientWidth < stage.clientHeight ? 0.62 : 1;
    const planes = [];
    const addPlane = (src, w, h, { x = 0, y = 0, z = 0, rx = 0, ry = 0, rz = 0, soft = true, opacity = 1 } = {}) => {
        const mat = new THREE.MeshBasicMaterial({
            map: tex(src), alphaMap: soft ? vignette : null, transparent: true, opacity, depthWrite: false, fog: true,
        });
        const mesh = new THREE.Mesh(new THREE.PlaneGeometry(w * k, h * k), mat);
        mesh.position.set(x * k, y * k, z);
        mesh.rotation.set(rx, ry, rz);
        mesh.userData.opacity = opacity;
        scene.add(mesh);
        planes.push(mesh);
        return mesh;
    };

    /* ---------- Σταθμοί ---------- */
    const N = stations.length;
    const total = (N - 1) * spacing;
    const bricks = []; // τούβλα που πετάγονται στον σταθμό του T-Rex
    stations.forEach((st, i) => {
        const z0 = -i * spacing;
        for (const l of st.layers) {
            addPlane(l.src, l.w, l.h, { x: l.x || 0, y: l.y || 0, z: z0 - (l.dz || 0), ry: l.ry || 0, rz: l.rz || 0, soft: l.soft !== false, opacity: l.opacity ?? 1 });
        }
        if (st.bricks) {
            const geo = new THREE.BoxGeometry(0.9, 0.42, 0.4);
            for (let k = 0; k < 26; k++) {
                const mat = new THREE.MeshBasicMaterial({ color: new THREE.Color().setHSL(0.04 + Math.random() * 0.03, 0.55, 0.22 + Math.random() * 0.2), fog: true });
                const b = new THREE.Mesh(geo, mat);
                const ang = Math.random() * Math.PI * 2, rad = 1 + Math.random() * 2.5;
                b.userData = {
                    station: i,
                    x0: Math.cos(ang) * rad, y0: Math.sin(ang) * rad * 0.7 - 0.5,
                    vx: Math.cos(ang) * (4 + Math.random() * 6), vy: Math.sin(ang) * (3 + Math.random() * 4),
                    vz: 10 + Math.random() * 10, spin: (Math.random() - 0.5) * 6, s: 0.6 + Math.random() * 0.9,
                };
                b.scale.setScalar(b.userData.s);
                b.position.set(b.userData.x0, b.userData.y0, z0 - 0.3);
                scene.add(b); bricks.push(b);
            }
        }
    });

    /* ---------- Σωματίδια (σπίθες/σκόνη) ---------- */
    const P = 1800;
    const pos = new Float32Array(P * 3), vel = new Float32Array(P), col = new Float32Array(P * 3);
    const palette = [new THREE.Color(0xffd400), new THREE.Color(0xff6a00), new THREE.Color(0xbfd9ff), new THREE.Color(0xffffff)];
    for (let i = 0; i < P; i++) {
        pos[i * 3] = (Math.random() - 0.5) * 36;
        pos[i * 3 + 1] = (Math.random() - 0.5) * 20;
        pos[i * 3 + 2] = 10 - Math.random() * (total + 30);
        vel[i] = 0.004 + Math.random() * 0.02;
        const c = palette[Math.floor(Math.random() * palette.length)];
        col[i * 3] = c.r; col[i * 3 + 1] = c.g; col[i * 3 + 2] = c.b;
    }
    const pGeo = new THREE.BufferGeometry();
    pGeo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
    pGeo.setAttribute('color', new THREE.BufferAttribute(col, 3));
    const points = new THREE.Points(pGeo, new THREE.PointsMaterial({
        size: 0.14, map: makeDotTexture(), vertexColors: true, transparent: true, opacity: 0.85,
        blending: THREE.AdditiveBlending, depthWrite: false, sizeAttenuation: true, fog: true,
    }));
    scene.add(points);

    /* ---------- Κατάσταση ---------- */
    const state = { p: 0, pSmooth: 0, mx: 0, my: 0, camX: 0, camY: 0, visible: true };
    ScrollTrigger.create({
        trigger: root, start: 'top top', end: 'bottom bottom',
        onUpdate: (self) => { state.p = self.progress; },
    });
    if (!matchMedia('(hover: none)').matches) {
        stage.addEventListener('pointermove', (e) => {
            const r = stage.getBoundingClientRect();
            state.mx = (e.clientX - r.left) / r.width - 0.5;
            state.my = (e.clientY - r.top) / r.height - 0.5;
        }, { passive: true });
        stage.addEventListener('pointerleave', () => { state.mx = 0; state.my = 0; });
    }

    const resize = () => {
        const w = stage.clientWidth, h = stage.clientHeight;
        renderer.setSize(w, h, false);
        camera.aspect = w / h;
        camera.fov = w < h ? 78 : 55; // στα κινητά πιο ανοιχτός φακός
        camera.updateProjectionMatrix();
    };
    resize();
    window.addEventListener('resize', resize, { passive: true });

    let last = performance.now();
    const tmp = new THREE.Vector3();
    const render = () => {
        if (!state.visible) return;
        const now = performance.now();
        const dt = Math.min((now - last) / 1000, 0.05);
        last = now;
        // ομαλή προσέγγιση της θέσης scroll (όταν ο χρήστης προτιμά λιγότερη κίνηση: άμεση)
        state.pSmooth += (state.p - state.pSmooth) * (reduce ? 1 : 0.08);
        const s = state.pSmooth * (N - 1); // θέση σε «σταθμούς»

        // κάμερα
        state.camX += (state.mx * 2.2 - state.camX) * 0.05;
        state.camY += (-state.my * 1.4 - state.camY) * 0.05;
        camera.position.set(state.camX, state.camY, 7 - state.pSmooth * total);
        tmp.set(state.camX * 0.35, state.camY * 0.35, camera.position.z - 12);
        camera.lookAt(tmp);

        // επίπεδα: σβήνουν όσο η κάμερα τα πλησιάζει/προσπερνά, ώστε να μη «χτυπάμε» πάνω τους
        for (const m of planes) {
            const d = camera.position.z - m.position.z; // >0 όσο το επίπεδο είναι μπροστά
            const fade = THREE.MathUtils.smoothstep(d, 1.5, 6);
            m.material.opacity = m.userData.opacity * fade;
            m.visible = fade > 0.01;
        }

        // σωματίδια: ανεβαίνουν αργά και ανακυκλώνονται γύρω από την κάμερα
        const arr = pGeo.attributes.position.array;
        for (let i = 0; i < P; i++) {
            arr[i * 3 + 1] += vel[i] * dt * 60;
            if (arr[i * 3 + 1] > 10) arr[i * 3 + 1] = -10;
            const dz = arr[i * 3 + 2] - camera.position.z;
            if (dz > 12) arr[i * 3 + 2] -= total + 30;
            if (dz < -(total + 18)) arr[i * 3 + 2] += total + 30;
        }
        pGeo.attributes.position.needsUpdate = true;

        // τούβλα: όσο πλησιάζει η κάμερα τον σταθμό, πετάγονται προς τα εμπρός
        for (const b of bricks) {
            const u = b.userData;
            const d = s - u.station; // <0 πριν φτάσουμε, 0 πάνω στον σταθμό
            const t = THREE.MathUtils.clamp((d + 0.45) / 0.75, 0, 1); // ξεκινούν λίγο πριν φτάσουμε στον τοίχο
            const e = t * t;
            b.position.set(u.x0 + u.vx * e, u.y0 + u.vy * e - 3 * e * e, -u.station * spacing - 0.3 + u.vz * e);
            b.rotation.set(u.spin * e, u.spin * 0.7 * e, u.spin * 0.4 * e);
            b.visible = t > 0.001;
        }

        // λεζάντες: ορατές κοντά στον σταθμό τους, με μικρή κίνηση σε βάθος
        captions.forEach((c) => {
            const i = parseFloat(c.dataset.station);
            const d = s - i;
            const vis = THREE.MathUtils.clamp(1 - Math.abs(d) * 2.6, 0, 1); // μόνο μία λεζάντα ορατή κάθε φορά
            c.style.opacity = vis;
            c.style.transform = `translate3d(0, ${d * -70}px, 0) scale(${1 + d * 0.14})`;
            c.style.pointerEvents = vis > 0.6 ? 'auto' : 'none';
            c.style.visibility = vis > 0 ? 'visible' : 'hidden';
        });
        root.style.setProperty('--journey-p', state.pSmooth.toFixed(4));

        renderer.render(scene, camera);
        requestAnimationFrame(render);
    };

    new IntersectionObserver(([e]) => {
        const was = state.visible;
        state.visible = e.isIntersecting;
        if (state.visible && !was) { last = performance.now(); render(); }
    }, { threshold: 0 }).observe(root);
    render();
    return { state };
}

/* Ακτινωτή διαφάνεια: τα άκρα κάθε εικόνας σβήνουν στο σκοτάδι */
function makeVignetteTexture() {
    const c = document.createElement('canvas');
    c.width = c.height = 512;
    const g = c.getContext('2d');
    const grad = g.createRadialGradient(256, 256, 60, 256, 256, 256);
    grad.addColorStop(0, '#fff'); grad.addColorStop(0.62, '#fff'); grad.addColorStop(1, '#000');
    g.fillStyle = grad; g.fillRect(0, 0, 512, 512);
    const t = new THREE.CanvasTexture(c);
    return t;
}

/* Μαλακή κουκκίδα για τα σωματίδια */
function makeDotTexture() {
    const c = document.createElement('canvas');
    c.width = c.height = 64;
    const g = c.getContext('2d');
    const grad = g.createRadialGradient(32, 32, 0, 32, 32, 32);
    grad.addColorStop(0, 'rgba(255,255,255,1)'); grad.addColorStop(0.4, 'rgba(255,255,255,.6)'); grad.addColorStop(1, 'rgba(255,255,255,0)');
    g.fillStyle = grad; g.fillRect(0, 0, 64, 64);
    const t = new THREE.CanvasTexture(c);
    t.colorSpace = THREE.SRGBColorSpace;
    return t;
}
