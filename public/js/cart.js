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