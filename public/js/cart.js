import './bootstrap';

/* ---------------- SCROLL REVEAL ---------------- */
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

document.querySelectorAll('.reveal')
    .forEach(el => revealObserver.observe(el));


/* ---------------- TOAST ---------------- */
window.showToast = function (msg) {

    let wrap = document.getElementById('toast-wrap');

    if (!wrap) {
        wrap = document.createElement('div');
        wrap.id = 'toast-wrap';
        wrap.style.position = 'fixed';
        wrap.style.bottom = '20px';
        wrap.style.right = '20px';
        wrap.style.zIndex = '9999';
        document.body.appendChild(wrap);
    }

    const toast = document.createElement('div');
    toast.className = 'toast-msg';
    toast.innerHTML = `<span>${msg}</span>`;
    wrap.appendChild(toast);

    requestAnimationFrame(() =>
        requestAnimationFrame(() =>
            toast.classList.add('show')
        )
    );

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 3000);
};


/* ---------------- ADD TO CART ---------------- */
    window.addToCart = function (productId, name) {

        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .content
            },
            body: JSON.stringify({
                product_id: productId,
                qty: 1
            })
        })
        .then(r => r.json())
        .then(data => {

            if (!data.success) {
                showToast(data.message || 'Failed to add product');
                return;
            }

            // Update count
            document.querySelectorAll('#cartCount, #cartBadge')
                .forEach(el => el.textContent = data.cartCount);

            // Refresh cart
            if (data.cartHtml) {
                document.getElementById('cartBody').innerHTML = data.cartHtml;
            }
            // Update total
            document.getElementById('cartTotal').textContent =
                '₹' + data.cartTotal;

            showToast(`✓ "${name}" added to cart`);
        })
        .catch(() => showToast('Something went wrong.'));
    };


/* ---------------- UPDATE QTY ---------------- */
window.updateQty = function (id, delta) {

    const qtyEl = document.getElementById('qty-' + id);
    if (!qtyEl) return;

    let newQty = parseInt(qtyEl.textContent) + delta;

    if (newQty <= 0) {
        window.removeFromCart(id);
        return;
    }

    fetch('/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .content
        },
        body: JSON.stringify({
            product_id: id,
            qty: newQty
        })
    })
    .then(r => r.json())
    .then(data => {

        if (!data.success) return;

        document.getElementById('cartBody').innerHTML =
            data.cartHtml;

        document.querySelectorAll('#cartCount, #cartBadge')
            .forEach(el => el.textContent = data.cartCount);

        document.getElementById('cartTotal').textContent =
            '₹' + data.cartTotal;
    });
};


/* ---------------- REMOVE ITEM ---------------- */
window.removeFromCart = function (id) {

    fetch('/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .content
        },
        body: JSON.stringify({ product_id: id })
    })
    .then(r => r.json())
    .then(data => {

        if (!data.success) return;

        document.getElementById('cartBody').innerHTML =
            data.cartHtml;

        document.querySelectorAll('#cartCount, #cartBadge')
            .forEach(el => el.textContent = data.cartCount);

        document.getElementById('cartTotal').textContent =
            '₹' + data.cartTotal;

        showToast('Item removed from cart');
    });
};


/* ---------------- WISHLIST ---------------- */
window.toggleWishlist = function (btn, productId) {

    const icon = btn.querySelector('i');

    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .content
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(r => r.json())
    .then(data => {

        if (!data.success) return;

        icon.classList.toggle('bi-heart', !data.added);
        icon.classList.toggle('bi-heart-fill', data.added);
        btn.classList.toggle('active', data.added);

        showToast(
            data.added
                ? '♥ Added to wishlist'
                : 'Removed from wishlist'
        );
    });
};
/* ── Hero Interactive Gift Game ── */
/* ═══════════════════════════════════════════════════════
   HERO INTERACTIVE GIFT GAME
   Font Awesome 6 Solid icons rendered on Canvas
   — Click / tap the floating icons to collect gifts!
═══════════════════════════════════════════════════════ */
(function () {
    const canvas = document.getElementById('heroCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');

    let W, H;
    let orbs      = [];
    let gifts     = [];
    let confettis = [];
    let popTexts  = [];
    let score     = 0;
    let scoreEl;
    let hintAlpha = 1;
    let hintTimer = 0;
    const mouse   = { x: -999, y: -999 };

    /* ────────────────────────────────
       Score badge (DOM overlay)
    ──────────────────────────────── */
    function createScoreEl() {
        scoreEl = document.createElement('div');
        scoreEl.id = 'heroScore';
        scoreEl.style.cssText = `
            position: absolute;
            top: 14px;
            right: 16px;
            z-index: 10;
            background: white;
            border-radius: 50px;
            padding: 5px 15px;
            font-family: 'Poppins', sans-serif;
            font-size: .75rem;
            font-weight: 700;
            color: var(--primary);
            box-shadow: 0 4px 16px rgba(255,77,109,.22);
            display: flex;
            align-items: center;
            gap: 7px;
            transition: transform .15s ease;
            pointer-events: none;
            user-select: none;
        `;
        scoreEl.innerHTML =
            `<i class="fa-solid fa-gift" style="font-size:.82rem"></i>` +
            `<span id="scoreNum">0</span> collected!`;
        canvas.parentElement.style.position = 'relative';
        canvas.parentElement.appendChild(scoreEl);
    }

    function bumpScore() {
        score++;
        document.getElementById('scoreNum').textContent = score;
        scoreEl.style.transform = 'scale(1.22)';
        setTimeout(() => scoreEl.style.transform = 'scale(1)', 160);
    }

    /* ────────────────────────────────
       Background pink orbs
    ──────────────────────────────── */
    const PALETTES = [
        [255, 77,  109],
        [255, 143, 171],
        [255, 180, 197],
        [220, 80,  120],
    ];

    class Orb {
        constructor() { this.reset(true); }
        reset(init = false) {
            this.x        = Math.random() * W;
            this.y        = init ? Math.random() * H : Math.random() * H;
            this.r        = 3 + Math.random() * 6;
            this.vx       = (Math.random() - .5) * .35;
            this.vy       = (Math.random() - .5) * .35;
            this.alpha    = 0;
            this.maxAlpha = .08 + Math.random() * .18;
            this.fadeIn   = true;
            this.life     = 0;
            this.maxLife  = 300 + Math.random() * 400;
            this.color    = PALETTES[Math.floor(Math.random() * PALETTES.length)];
            this.pulse    = Math.random() * Math.PI * 2;
        }
        update() {
            this.life++;
            this.pulse += .035;
            const pf = 1 + Math.sin(this.pulse) * .1;
            this.vx *= .98; this.vy *= .98;
            this.x += this.vx; this.y += this.vy;
            if (this.fadeIn) {
                this.alpha = Math.min(this.alpha + .008, this.maxAlpha);
                if (this.alpha >= this.maxAlpha) this.fadeIn = false;
            }
            if (this.life > this.maxLife - 60) this.alpha -= .006;
            if (this.life > this.maxLife || this.alpha <= 0) { this.reset(); return; }
            const [r, g, b] = this.color;
            const radius    = this.r * pf;
            const grd = ctx.createRadialGradient(this.x, this.y, 0, this.x, this.y, radius * 5);
            grd.addColorStop(0,  `rgba(${r},${g},${b},${this.alpha})`);
            grd.addColorStop(.5, `rgba(${r},${g},${b},${this.alpha * .2})`);
            grd.addColorStop(1,  `rgba(${r},${g},${b},0)`);
            ctx.beginPath();
            ctx.arc(this.x, this.y, radius * 5, 0, Math.PI * 2);
            ctx.fillStyle = grd;
            ctx.fill();
            ctx.beginPath();
            ctx.arc(this.x, this.y, radius, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(${r},${g},${b},${Math.min(this.alpha * 3, .6)})`;
            ctx.fill();
        }
    }

    /* ────────────────────────────────
       Font Awesome icon definitions
       (FA 6 Solid Unicode codepoints)
    ──────────────────────────────── */
    const GIFT_ICONS = [
        { code: '\uf06b', color: '#ff4d6d' },   // fa-gift
        { code: '\uf5b5', color: '#ff8fab' },   // fa-gifts
        { code: '\uf466', color: '#e63b5c' },   // fa-ribbon
        { code: '\uf004', color: '#ff4d6d' },   // fa-heart
        { code: '\uf005', color: '#ffc107' },   // fa-star
        { code: '\uf517', color: '#ff8fab' },   // fa-birthday-cake
        { code: '\uf72b', color: '#c13584' },   // fa-candy-cane
        { code: '\uf4b9', color: '#ff4d6d' },   // fa-gem
    ];

    const POP_WORDS = ['+1', 'yay!', 'wow!', 'nice!', 'got it!', '💖'];

    class Gift {
        constructor() { this.reset(); }
        reset() {
            this.x           = 60 + Math.random() * (W - 120);
            this.y           = H + 60;
            this.size        = 22 + Math.random() * 18;
            this.vx          = (Math.random() - .5) * .8;
            this.vy          = -(0.55 + Math.random() * 0.75);
            this.wobble      = Math.random() * Math.PI * 2;
            this.wobbleSpeed = .02 + Math.random() * .02;
            this.spin        = 0;
            this.spinSpeed   = (Math.random() - .5) * .025;
            const pick       = GIFT_ICONS[Math.floor(Math.random() * GIFT_ICONS.length)];
            this.code        = pick.code;
            this.color       = pick.color;
            this.alpha       = 0;
            this.clicked     = false;
            this.popText     = POP_WORDS[Math.floor(Math.random() * POP_WORDS.length)];
        }
        update() {
            if (this.clicked) return;
            this.wobble += this.wobbleSpeed;
            this.spin   += this.spinSpeed;
            this.x      += this.vx + Math.sin(this.wobble) * .45;
            this.y      += this.vy;
            this.alpha   = Math.min(this.alpha + .03, 1);
            if (this.y < -80) this.reset();
        }
        draw() {
            if (this.clicked) return;
            ctx.save();
            ctx.globalAlpha  = this.alpha;
            ctx.translate(this.x, this.y);
            ctx.rotate(this.spin);
            ctx.font         = `900 ${this.size}px "Font Awesome 6 Free"`;
            ctx.fillStyle    = this.color;
            ctx.textAlign    = 'center';
            ctx.textBaseline = 'middle';
            ctx.shadowColor  = this.color + '70';
            ctx.shadowBlur   = 22;
            ctx.fillText(this.code, 0, 0);
            ctx.restore();
        }
        isHit(mx, my) {
            return Math.abs(mx - this.x) < this.size &&
                   Math.abs(my - this.y) < this.size;
        }
    }

    /* ────────────────────────────────
       Confetti pieces
    ──────────────────────────────── */
    class Confetti {
        constructor(x, y, mini = false) {
            this.x       = x; this.y = y;
            this.vx      = (Math.random() - .5) * (mini ? 3 : 8);
            this.vy      = mini ? (Math.random() - .5) * 2 : (-3 - Math.random() * 6);
            this.gravity = mini ? .04 : .23;
            this.size    = mini ? (2 + Math.random() * 3) : (5 + Math.random() * 6);
            this.color   = `hsl(${320 + Math.random() * 70},88%,${55 + Math.random() * 20}%)`;
            this.alpha   = 1;
            this.rot     = Math.random() * Math.PI * 2;
            this.rotV    = (Math.random() - .5) * .18;
            this.shape   = Math.random() > .5 ? 'rect' : 'circle';
        }
        update() {
            this.vy   += this.gravity;
            this.x    += this.vx;
            this.y    += this.vy;
            this.rot  += this.rotV;
            this.alpha -= .02;
        }
        draw() {
            if (this.alpha <= 0) return;
            ctx.save();
            ctx.globalAlpha = this.alpha;
            ctx.fillStyle   = this.color;
            ctx.translate(this.x, this.y);
            ctx.rotate(this.rot);
            if (this.shape === 'rect') {
                ctx.fillRect(-this.size / 2, -this.size / 4, this.size, this.size / 2);
            } else {
                ctx.beginPath();
                ctx.arc(0, 0, this.size / 2, 0, Math.PI * 2);
                ctx.fill();
            }
            ctx.restore();
        }
    }

    /* ────────────────────────────────
       Floating "+1 yay!" pop text
    ──────────────────────────────── */
    class PopText {
        constructor(x, y, text) {
            this.x = x; this.y = y; this.text = text;
            this.vy = -2; this.alpha = 1; this.scale = 0;
        }
        update() {
            this.y     += this.vy;
            this.vy    *= .96;
            this.alpha -= .024;
            this.scale  = Math.min(this.scale + .14, 1);
        }
        draw() {
            if (this.alpha <= 0) return;
            ctx.save();
            ctx.globalAlpha  = this.alpha;
            ctx.font         = `700 ${Math.round(17 * this.scale)}px 'Poppins',sans-serif`;
            ctx.fillStyle    = '#ff4d6d';
            ctx.textAlign    = 'center';
            ctx.textBaseline = 'middle';
            ctx.shadowColor  = 'rgba(255,77,109,.45)';
            ctx.shadowBlur   = 8;
            ctx.fillText(this.text, this.x, this.y);
            ctx.restore();
        }
    }

    /* ────────────────────────────────
       Helpers
    ──────────────────────────────── */
    function spawnBurst(x, y) {
        for (let i = 0; i < 36; i++) confettis.push(new Confetti(x, y, false));
    }

    function handleClick(e) {
        const rect   = canvas.getBoundingClientRect();
        const scaleX = W / rect.width;
        const scaleY = H / rect.height;
        const cx = (e.clientX - rect.left) * scaleX;
        const cy = (e.clientY - rect.top)  * scaleY;
        let hit = false;
        gifts.forEach(g => {
            if (!g.clicked && g.isHit(cx, cy)) {
                g.clicked = true;
                spawnBurst(g.x, g.y);
                popTexts.push(new PopText(g.x, g.y - 36, g.popText));
                bumpScore();
                hit = true;
                setTimeout(() => g.reset(), 550);
            }
        });
        // Small sparkle burst even on miss
        if (!hit) {
            for (let i = 0; i < 8; i++) confettis.push(new Confetti(cx, cy, true));
        }
    }

    /* ────────────────────────────────
       Events
    ──────────────────────────────── */
    canvas.addEventListener('mousemove', e => {
        const rect = canvas.getBoundingClientRect();
        mouse.x = (e.clientX - rect.left) * (W / rect.width);
        mouse.y = (e.clientY - rect.top)  * (H / rect.height);
        // Sparkle trail
        if (Math.random() < .22) confettis.push(new Confetti(mouse.x, mouse.y, true));
        // Pointer cursor when hovering a gift
        canvas.style.cursor = gifts.some(g => !g.clicked && g.isHit(mouse.x, mouse.y))
            ? 'pointer' : 'default';
    });
    canvas.addEventListener('mouseleave', () => { mouse.x = -999; mouse.y = -999; });
    canvas.addEventListener('click', handleClick);
    canvas.addEventListener('touchstart', e => {
        e.preventDefault();
        const t = e.touches[0];
        handleClick({ clientX: t.clientX, clientY: t.clientY });
    }, { passive: false });

    /* ────────────────────────────────
       Draw helpers
    ──────────────────────────────── */
    function drawConnections() {
        for (let i = 0; i < orbs.length; i++) {
            for (let j = i + 1; j < orbs.length; j++) {
                const a = orbs[i], b = orbs[j];
                const dx = a.x - b.x, dy = a.y - b.y;
                const d  = Math.sqrt(dx * dx + dy * dy);
                if (d < 100) {
                    ctx.beginPath();
                    ctx.moveTo(a.x, a.y);
                    ctx.lineTo(b.x, b.y);
                    ctx.strokeStyle = `rgba(255,77,109,${(1 - d / 100) * .06})`;
                    ctx.lineWidth   = .6;
                    ctx.stroke();
                }
            }
        }
    }

    function drawHint() {
        if (hintAlpha <= 0) return;
        hintTimer++;
        if (hintTimer > 200) hintAlpha -= .01;
        ctx.save();
        ctx.globalAlpha  = hintAlpha * .55;
        ctx.font         = "500 12px 'Poppins',sans-serif";
        ctx.fillStyle    = '#ff4d6d';
        ctx.textAlign    = 'center';
        ctx.fillText('✨  Click the floating icons to collect gifts!  ✨', W / 2, H - 20);
        ctx.restore();
    }

    /* ────────────────────────────────
       Main render loop
    ──────────────────────────────── */
    function loop() {
        ctx.clearRect(0, 0, W, H);
        drawConnections();
        orbs.forEach(o => o.update());
        gifts.forEach(g => { g.update(); g.draw(); });
        confettis = confettis.filter(c => c.alpha > 0);
        confettis.forEach(c => { c.update(); c.draw(); });
        popTexts  = popTexts.filter(p => p.alpha > 0);
        popTexts.forEach(p => { p.update(); p.draw(); });
        drawHint();
        requestAnimationFrame(loop);
    }

    function resize() {
        W = canvas.width  = canvas.offsetWidth;
        H = canvas.height = canvas.offsetHeight;
    }

    function init() {
        resize();
        orbs  = Array.from({ length: 90 }, () => new Orb());
        gifts = Array.from({ length: 7  }, () => {
            const g = new Gift();
            g.y = Math.random() * H;   // scatter on load instead of all coming from bottom
            return g;
        });
        createScoreEl();
        loop();
    }

    window.addEventListener('resize', resize);
    init();
})();
/* ── Go To Top ── */
(function () {
    const btn = document.getElementById('goTop');
    if (!btn) return;

    window.addEventListener('scroll', () => {
        btn.classList.toggle('visible', window.scrollY > 300);
    }, { passive: true });

    btn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
})();
