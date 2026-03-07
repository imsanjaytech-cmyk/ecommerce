/* ---------------- TOAST ---------------- */
function showToast(msg) {
    let wrap = document.getElementById('toast-wrap');
    if (!wrap) {
        wrap = document.createElement('div');
        wrap.id = 'toast-wrap';
        wrap.style.cssText = 'position:fixed;bottom:20px;right:20px;z-index:9999;';
        document.body.appendChild(wrap);
    }
    const toast = document.createElement('div');
    toast.className = 'toast-msg';
    toast.innerHTML = `<span>${msg}</span>`;
    wrap.appendChild(toast);
    requestAnimationFrame(() => requestAnimationFrame(() => toast.classList.add('show')));
    setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 400); }, 3000);
}

/* ---------------- ADD TO CART ---------------- */
function addToCart(productId, name) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ product_id: productId, qty: 1 })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) { showToast(data.message || 'Failed to add product'); return; }
        document.querySelectorAll('#cartCount, #cartBadge').forEach(el => el.textContent = data.cartCount);
        if (data.cartHtml) document.getElementById('cartBody').innerHTML = data.cartHtml;
        document.getElementById('cartTotal').textContent = '₹' + data.cartTotal;
        showToast(`✓ "${name}" added to cart`);

        /* 🎁 Trigger gift burst on successful add */
        spawnCartGiftBurst();
    })
    .catch(() => showToast('Something went wrong.'));
}

/* ---------------- UPDATE QTY ---------------- */
function updateQty(id, delta) {
    const qtyEl = document.getElementById('qty-' + id);
    if (!qtyEl) return;
    let newQty = parseInt(qtyEl.textContent) + delta;
    if (newQty <= 0) { removeFromCart(id); return; }
    fetch('/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ product_id: id, qty: newQty })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) return;
        document.getElementById('cartBody').innerHTML = data.cartHtml;
        document.querySelectorAll('#cartCount, #cartBadge').forEach(el => el.textContent = data.cartCount);
        document.getElementById('cartTotal').textContent = '₹' + data.cartTotal;
    });
}

/* ---------------- REMOVE ITEM ---------------- */
function removeFromCart(id) {
    fetch('/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ product_id: id })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) return;
        document.getElementById('cartBody').innerHTML = data.cartHtml;
        document.querySelectorAll('#cartCount, #cartBadge').forEach(el => el.textContent = data.cartCount);
        document.getElementById('cartTotal').textContent = '₹' + data.cartTotal;
        showToast('Item removed from cart');
    });
}

/* ---------------- WISHLIST ---------------- */
function toggleWishlist(btn, productId) {
    const icon = btn.querySelector('i');
    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) return;
        icon.classList.toggle('bi-heart', !data.added);
        icon.classList.toggle('bi-heart-fill', data.added);
        btn.classList.toggle('active', data.added);
        showToast(data.added ? '♥ Added to wishlist' : 'Removed from wishlist');
    });
}


/* ═══════════════════════════════════════════════════════
   🎁 CART GIFT FLOAT ANIMATION
   Floating FA icons burst from bottom-right (cart area)
   whenever an item is successfully added to cart.
   Requires Font Awesome 6 Free to be loaded on the page.
═══════════════════════════════════════════════════════ */
(function () {

    /* ── Canvas setup (fixed overlay, pointer-events:none) ── */
    let canvas, ctx, W, H;
    let particles = [];
    let rafId     = null;
    let running   = false;

    const GIFT_ICONS = [
        { code: '\uf06b', color: '#ff4d6d' },   // fa-gift
        { code: '\uf5b5', color: '#ff8fab' },   // fa-gifts
        { code: '\uf004', color: '#e63b5c' },   // fa-heart
        { code: '\uf005', color: '#ffc107' },   // fa-star
        { code: '\uf4b9', color: '#c13584' },   // fa-gem
    ];

    const CONFETTI_COLORS = [
        '#ff4d6d','#ff8fab','#ffc107','#1f9c4a','#1a7cd4','#c13584'
    ];

    function ensureCanvas() {
        if (canvas) return;

        canvas = document.createElement('canvas');
        canvas.id = 'cartGiftCanvas';
        canvas.style.cssText = `
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 99998;
        `;
        document.body.appendChild(canvas);
        ctx = canvas.getContext('2d');
        resize();
        window.addEventListener('resize', resize, { passive: true });
    }

    function resize() {
        W = canvas.width  = window.innerWidth;
        H = canvas.height = window.innerHeight;
    }

    /* ── Floating Gift Icon particle ── */
    class GiftParticle {
        constructor(ox, oy) {
            const pick    = GIFT_ICONS[Math.floor(Math.random() * GIFT_ICONS.length)];
            this.code     = pick.code;
            this.color    = pick.color;
            this.x        = ox;
            this.y        = oy;
            this.size     = 18 + Math.random() * 22;
            this.vx       = (Math.random() - .5) * 5;
            this.vy       = -(3 + Math.random() * 5);
            this.gravity  = 0.12;
            this.wobble   = Math.random() * Math.PI * 2;
            this.wSpeed   = .03 + Math.random() * .03;
            this.spin     = 0;
            this.spinV    = (Math.random() - .5) * .06;
            this.alpha    = 1;
            this.dead     = false;
        }
        update() {
            this.wobble += this.wSpeed;
            this.spin   += this.spinV;
            this.vy     += this.gravity;
            this.x      += this.vx + Math.sin(this.wobble) * .7;
            this.y      += this.vy;
            this.alpha  -= 0.018;
            if (this.alpha <= 0 || this.y > H + 60) this.dead = true;
        }
        draw() {
            ctx.save();
            ctx.globalAlpha  = Math.max(this.alpha, 0);
            ctx.translate(this.x, this.y);
            ctx.rotate(this.spin);
            ctx.font         = `900 ${this.size}px "Font Awesome 6 Free"`;
            ctx.fillStyle    = this.color;
            ctx.textAlign    = 'center';
            ctx.textBaseline = 'middle';
            ctx.shadowColor  = this.color + '88';
            ctx.shadowBlur   = 18;
            ctx.fillText(this.code, 0, 0);
            ctx.restore();
        }
    }

    /* ── Confetti piece ── */
    class ConfettiPiece {
        constructor(ox, oy) {
            this.x      = ox;
            this.y      = oy;
            this.vx     = (Math.random() - .5) * 9;
            this.vy     = -(2 + Math.random() * 7);
            this.gravity= 0.2;
            this.size   = 5 + Math.random() * 6;
            this.color  = CONFETTI_COLORS[Math.floor(Math.random() * CONFETTI_COLORS.length)];
            this.alpha  = 1;
            this.rot    = Math.random() * Math.PI * 2;
            this.rotV   = (Math.random() - .5) * .2;
            this.shape  = Math.random() > .5 ? 'rect' : 'circle';
            this.dead   = false;
        }
        update() {
            this.vy    += this.gravity;
            this.x     += this.vx;
            this.y     += this.vy;
            this.rot   += this.rotV;
            this.alpha -= 0.022;
            if (this.alpha <= 0 || this.y > H + 40) this.dead = true;
        }
        draw() {
            ctx.save();
            ctx.globalAlpha = Math.max(this.alpha, 0);
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

    /* ── Pop text "+1 yay!" ── */
    class PopText {
        constructor(x, y) {
            const words = ['+1', 'yay!', 'wow!', 'nice!', '💖', 'added!'];
            this.x     = x;
            this.y     = y;
            this.text  = words[Math.floor(Math.random() * words.length)];
            this.vy    = -2.2;
            this.alpha = 1;
            this.scale = 0;
            this.dead  = false;
        }
        update() {
            this.y     += this.vy;
            this.vy    *= .95;
            this.alpha -= 0.022;
            this.scale  = Math.min(this.scale + .15, 1);
            if (this.alpha <= 0) this.dead = true;
        }
        draw() {
            ctx.save();
            ctx.globalAlpha  = Math.max(this.alpha, 0);
            ctx.font         = `700 ${Math.round(18 * this.scale)}px 'Poppins',sans-serif`;
            ctx.fillStyle    = '#ff4d6d';
            ctx.textAlign    = 'center';
            ctx.textBaseline = 'middle';
            ctx.shadowColor  = 'rgba(255,77,109,.5)';
            ctx.shadowBlur   = 10;
            ctx.fillText(this.text, this.x, this.y);
            ctx.restore();
        }
    }

    /* ── Render loop ── */
    function loop() {
        ctx.clearRect(0, 0, W, H);
        particles.forEach(p => { p.update(); p.draw(); });
        particles = particles.filter(p => !p.dead);

        if (particles.length === 0) {
            running = false;
            return; // stop rAF when nothing left — no idle cost
        }
        rafId = requestAnimationFrame(loop);
    }

    /* ── Public: burst origin = cart icon position (top-right) ── */
    window.spawnCartGiftBurst = function () {
        ensureCanvas();

        /* Try to burst from the cart badge/icon if it exists in DOM */
        let ox = W - 60, oy = 60;
        const cartIcon = document.getElementById('cartCount')
                      || document.getElementById('cartBadge');
        if (cartIcon) {
            const r = cartIcon.getBoundingClientRect();
            ox = r.left + r.width  / 2;
            oy = r.top  + r.height / 2;
        }

        /* Spawn gift icons */
        for (let i = 0; i < 7; i++)  particles.push(new GiftParticle(ox, oy));
        /* Spawn confetti */
        for (let i = 0; i < 28; i++) particles.push(new ConfettiPiece(ox, oy));
        /* Spawn pop text */
        particles.push(new PopText(ox, oy - 30));

        if (!running) {
            running = true;
            rafId   = requestAnimationFrame(loop);
        }
    };

})();
