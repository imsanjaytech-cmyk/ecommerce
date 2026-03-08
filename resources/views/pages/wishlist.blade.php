@extends('layouts.app')
@section('title', 'My Wishlist — Shanas')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap');

:root {
    --pink:      #ff4d6d;
    --pink-d:    #e8304d;
    --pink-soft: #fff0f3;
    --pink-bd:   #ffe4ea;
    --rose:      #ff8fab;
    --page-bg:   #f7f8fa;
    --border:    #e8eaed;
    --text:      #0f1111;
    --muted:     #8a8f98;
    --card-bg:   #ffffff;
}

* { box-sizing: border-box; }
body { background: var(--page-bg); font-family: 'Poppins', sans-serif; }

/* ── Hero ── */
.wl-hero {
    background: linear-gradient(135deg, #fff0f3 0%, #fdf5f7 50%, #f5f0ff 100%);
    border-bottom: 1px solid var(--pink-bd);
    padding: 44px 0 32px;
    position: relative; overflow: hidden;
}
.wl-hero::before {
    content: '♡';
    position: absolute; right: 5%; top: 50%;
    transform: translateY(-50%);
    font-size: 180px; color: rgba(255,77,109,.05);
    line-height: 1; pointer-events: none; user-select: none;
}
.wl-hero-inner { position: relative; z-index: 1; }

.wl-hero h1 {
    font-size: clamp(1.7rem, 4vw, 2.4rem);
    font-weight: 800; color: var(--text);
    margin: 0 0 6px; line-height: 1.15;
    letter-spacing: -.02em;
}
.wl-hero h1 span { color: var(--pink); }
.wl-hero p { color: var(--muted); font-size: .85rem; margin: 0; font-weight: 500; }

.wl-count-pill {
    display: inline-flex; align-items: center; gap: 6px;
    background: white; border: 1.5px solid var(--pink-bd);
    padding: 5px 14px; border-radius: 30px;
    font-size: .75rem; font-weight: 700; color: var(--pink);
    margin-top: 14px; box-shadow: 0 2px 12px rgba(255,77,109,.1);
}
.wl-count-pill i { font-size: .8rem; }

/* ── Main wrap ── */
.wl-wrap { max-width: 1160px; margin: 0 auto; padding: 32px 20px 80px; }

/* ── Top bar ── */
.wl-topbar {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 12px; margin-bottom: 26px;
}
.wl-topbar-left {
    font-size: .8rem; font-weight: 600; color: var(--muted);
}
.wl-topbar-left strong { color: var(--text); }

.btn-sm-ghost {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 15px; border-radius: 8px;
    background: white; border: 1.5px solid var(--border);
    font-size: .73rem; font-weight: 600; color: var(--muted);
    cursor: pointer; font-family: 'Poppins', sans-serif;
    transition: all .18s; text-decoration: none;
}
.btn-sm-ghost:hover { border-color: var(--pink); color: var(--pink); background: var(--pink-soft); }

.btn-sm-pink {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 16px; border-radius: 8px;
    background: linear-gradient(135deg, var(--pink), var(--pink-d));
    border: none; font-size: .73rem; font-weight: 700;
    color: white; cursor: pointer; font-family: 'Poppins', sans-serif;
    text-decoration: none; transition: all .2s;
    box-shadow: 0 3px 12px rgba(255,77,109,.25);
}
.btn-sm-pink:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(255,77,109,.35); color: white; }

/* ── Grid ── */
.wl-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(235px, 1fr));
    gap: 20px;
}

/* ── Card ── */
.wl-card {
    background: var(--card-bg);
    border-radius: 16px;
    border: 1.5px solid var(--border);
    overflow: hidden;
    position: relative;
    transition: transform .28s cubic-bezier(.34,1.56,.64,1), box-shadow .28s;
    animation: fadeUp .4s ease both;
}
.wl-card:nth-child(1) { animation-delay: .04s; }
.wl-card:nth-child(2) { animation-delay: .08s; }
.wl-card:nth-child(3) { animation-delay: .12s; }
.wl-card:nth-child(4) { animation-delay: .16s; }
.wl-card:nth-child(5) { animation-delay: .20s; }
.wl-card:nth-child(6) { animation-delay: .24s; }
.wl-card:nth-child(7) { animation-delay: .28s; }
.wl-card:nth-child(8) { animation-delay: .32s; }
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(22px); }
    to   { opacity: 1; transform: translateY(0); }
}
.wl-card:hover {
    transform: translateY(-7px);
    box-shadow: 0 20px 50px rgba(0,0,0,.1);
}

/* Image */
.wl-img-wrap {
    aspect-ratio: 1 / 1;
    overflow: hidden;
    background: var(--pink-soft);
    position: relative;
}
.wl-img-wrap img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform .5s ease;
    display: block;
}
.wl-card:hover .wl-img-wrap img { transform: scale(1.08); }

/* Placeholder */
.wl-img-ph {
    width: 100%; height: 100%;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 8px;
    background: linear-gradient(135deg, #fff0f3 0%, #fde8ef 100%);
}
.wl-img-ph i { font-size: 2.8rem; color: var(--rose); opacity: .5; }
.wl-img-ph span { font-size: .65rem; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: .06em; }

/* Category badge */
.wl-cat-badge {
    position: absolute; top: 10px; left: 10px;
    background: rgba(255,255,255,.92); backdrop-filter: blur(6px);
    border: 1px solid var(--border); border-radius: 20px;
    padding: 3px 10px; font-size: .6rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .05em;
}

/* Remove button (heart icon top-right) */
.wl-remove-btn {
    position: absolute; top: 10px; right: 10px;
    width: 34px; height: 34px; border-radius: 50%;
    background: white; border: 1.5px solid var(--pink-bd);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all .2s;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
    color: var(--pink); font-size: .95rem;
}
.wl-remove-btn:hover {
    background: var(--pink); color: white;
    border-color: var(--pink);
    transform: scale(1.1);
}

/* Quick add overlay */
.wl-overlay {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: linear-gradient(to top, rgba(255,77,109,.92) 0%, transparent 100%);
    padding: 40px 14px 14px;
    transform: translateY(100%);
    transition: transform .28s ease;
    display: flex; align-items: flex-end;
}
.wl-card:hover .wl-overlay { transform: translateY(0); }
.btn-add-cart {
    width: 100%; padding: 9px; border-radius: 9px;
    background: white; border: none;
    font-size: .76rem; font-weight: 700; color: var(--pink);
    cursor: pointer; font-family: 'Poppins', sans-serif;
    display: flex; align-items: center; justify-content: center; gap: 6px;
    transition: all .18s;
}
.btn-add-cart:hover { background: var(--pink-soft); }

/* Card body */
.wl-card-body { padding: 14px 14px 16px; }
.wl-product-name {
    font-size: .85rem; font-weight: 600; color: var(--text);
    margin: 0 0 4px; line-height: 1.4;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
    text-decoration: none;
}
.wl-product-name:hover { color: var(--pink); }
.wl-product-cat {
    font-size: .68rem; color: var(--muted); font-weight: 500; margin-bottom: 10px;
}
.wl-product-footer {
    display: flex; align-items: center;
    justify-content: space-between; gap: 8px;
}
.wl-price { font-size: .95rem; font-weight: 800; color: var(--text); }
.wl-price .old-price {
    font-size: .72rem; font-weight: 500;
    color: var(--muted); text-decoration: line-through; margin-right: 4px;
}
.wl-stock-badge {
    font-size: .62rem; font-weight: 700; padding: 3px 8px;
    border-radius: 6px; text-transform: uppercase; letter-spacing: .04em;
}
.in-stock   { background: #f0fdf4; color: #15803d; }
.out-stock  { background: #fef2f2; color: #b91c1c; }

/* ── Empty state ── */
.wl-empty {
    text-align: center; padding: 80px 20px;
    background: white; border-radius: 20px;
    border: 1.5px dashed var(--pink-bd);
}
.wl-empty-icon {
    width: 90px; height: 90px; border-radius: 50%;
    background: var(--pink-soft); border: 2px solid var(--pink-bd);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px; font-size: 2.2rem; color: var(--rose);
    animation: heartbeat 2.5s ease infinite;
}
@keyframes heartbeat {
    0%, 100% { transform: scale(1); }
    14%       { transform: scale(1.15); }
    28%       { transform: scale(1); }
    42%       { transform: scale(1.1); }
    56%       { transform: scale(1); }
}
.wl-empty h3 {
    font-size: 1.2rem; font-weight: 700; color: var(--text);
    margin: 0 0 8px;
}
.wl-empty p {
    font-size: .82rem; color: var(--muted); margin: 0 0 24px;
    max-width: 280px; margin-left: auto; margin-right: auto; line-height: 1.6;
}
.btn-shop-now {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 12px 28px; border-radius: 12px;
    background: linear-gradient(135deg, var(--pink), var(--pink-d));
    color: white; font-size: .84rem; font-weight: 700;
    text-decoration: none; font-family: 'Poppins', sans-serif;
    box-shadow: 0 6px 20px rgba(255,77,109,.3);
    transition: all .2s;
}
.btn-shop-now:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(255,77,109,.4);
    color: white;
}

/* ── Toast ── */
.wl-toast {
    display: none; position: fixed;
    bottom: 28px; left: 50%; transform: translateX(-50%);
    padding: 11px 22px; border-radius: 10px;
    font-size: .8rem; font-weight: 700; font-family: 'Poppins', sans-serif;
    z-index: 9999; white-space: nowrap;
    box-shadow: 0 8px 24px rgba(0,0,0,.15);
    animation: toastIn .3s ease;
}
.wl-toast.success { background: #15803d; color: white; }
.wl-toast.info    { background: var(--pink); color: white; }
@keyframes toastIn {
    from { opacity: 0; transform: translateX(-50%) translateY(12px); }
    to   { opacity: 1; transform: translateX(-50%) translateY(0); }
}

@media (max-width: 600px) {
    .wl-hero { padding: 30px 0 24px; }
    .wl-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .wl-card-body { padding: 10px 10px 12px; }
    .wl-product-name { font-size: .78rem; }
    .wl-price { font-size: .85rem; }
}
</style>

{{-- Hero --}}
<div class="wl-hero">
    <div class="container wl-hero-inner">
        <h1>My <span>Wishlist</span></h1>
        <p>All the things you love, saved in one place.</p>
        <div class="wl-count-pill">
            <i class="bi bi-heart-fill"></i>
            {{ $products->count() }} item{{ $products->count() !== 1 ? 's' : '' }} saved
        </div>
    </div>
</div>

<div class="wl-toast" id="wlToast"></div>

<div class="wl-wrap">

    @if($products->isNotEmpty())

    {{-- Top bar --}}
    <div class="wl-topbar">
        <div class="wl-topbar-left">
            Showing <strong>{{ $products->count() }}</strong> saved item{{ $products->count() !== 1 ? 's' : '' }}
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('products.index') }}" class="btn-sm-ghost">
                <i class="bi bi-plus"></i> Add More
            </a>
            <button class="btn-sm-ghost" id="clearAllBtn"
                style="color:#b91c1c;border-color:#fecaca;"
                onmouseover="this.style.background='#fef2f2';this.style.borderColor='#b91c1c'"
                onmouseout="this.style.background='white';this.style.borderColor='#fecaca'">
                <i class="bi bi-trash3"></i> Clear All
            </button>
        </div>
    </div>

    {{-- Grid --}}
    <div class="wl-grid" id="wishlistGrid">
        @foreach($products as $product)
        <div class="wl-card" id="wl-card-{{ $product->id }}" data-id="{{ $product->id }}">

            {{-- Image --}}
            <div class="wl-img-wrap">
                @if($product->images && $product->images->first())
                    <img src="{{ $product->images->first()->image_url }}"
                         alt="{{ $product->name }}"
                         onerror="this.parentElement.innerHTML='<div class=\'wl-img-ph\'><i class=\'bi bi-bag-heart\'></i><span>No image</span></div>'">
                @elseif($product->image)
                    <img src="{{ $product->image }}"
                         alt="{{ $product->name }}"
                         onerror="this.parentElement.innerHTML='<div class=\'wl-img-ph\'><i class=\'bi bi-bag-heart\'></i><span>No image</span></div>'">
                @else
                    <div class="wl-img-ph">
                        <i class="bi bi-bag-heart"></i>
                        <span>No image</span>
                    </div>
                @endif

                {{-- Category badge --}}
                @if($product->category)
                <div class="wl-cat-badge">{{ $product->category->name }}</div>
                @endif

                {{-- Remove button --}}
                <button class="wl-remove-btn" onclick="removeFromWishlist({{ $product->id }}, this)"
                    title="Remove from wishlist">
                    <i class="bi bi-heart-fill"></i>
                </button>

                {{-- Add to cart overlay --}}
                <div class="wl-overlay">
                    <button class="btn-add-cart" onclick="addToCart({{ $product->id }}, this)">
                        <i class="bi bi-bag-plus"></i> Add to Cart
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div class="wl-card-body">
                <a href="{{ route('products.show', $product->slug) }}" class="wl-product-name d-block">
                    {{ $product->name }}
                </a>
                @if($product->category)
                <div class="wl-product-cat">{{ $product->category->name }}</div>
                @endif
                <div class="wl-product-footer">
                    <div class="wl-price">
                        @if($product->compare_price && $product->compare_price > $product->price)
                            <span class="old-price">₹{{ number_format($product->compare_price) }}</span>
                        @endif
                        ₹{{ number_format($product->price) }}
                    </div>
                    <span class="wl-stock-badge {{ ($product->stock > 0 || $product->track_inventory == false) ? 'in-stock' : 'out-stock' }}">
                        {{ ($product->stock > 0 || $product->track_inventory == false) ? 'In Stock' : 'Sold Out' }}
                    </span>
                </div>
            </div>

        </div>
        @endforeach
    </div>

    @else

    {{-- Empty state --}}
    <div class="wl-empty">
        <div class="wl-empty-icon">
            <i class="bi bi-heart"></i>
        </div>
        <h3>Your wishlist is empty</h3>
        <p>Save items you love by tapping the heart icon on any product.</p>
        <a href="{{ route('products.index') }}" class="btn-shop-now">
            <i class="bi bi-bag-heart"></i> Explore Collection
        </a>
    </div>

    @endif

</div>

<script>
const TOGGLE_URL = "{{ route('wishlist.toggle') }}";
const CART_URL   = "{{ route('cart.add') }}";
const CSRF       = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// ── Remove from wishlist ────────────────────────────────
async function removeFromWishlist(productId, btn) {
    btn.style.pointerEvents = 'none';
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';

    try {
        const res  = await fetch(TOGGLE_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ product_id: productId })
        });
        const data = await res.json();

        if (data.success) {
            const card = document.getElementById('wl-card-' + productId);
            card.style.transition = 'all .35s ease';
            card.style.opacity    = '0';
            card.style.transform  = 'scale(.85)';
            setTimeout(() => {
                card.remove();
                updateCount();
                showToast('💔 Removed from wishlist', 'info');
            }, 350);

            // Update header wishlist count if present
            const badge = document.querySelector('.wishlist-count');
            if (badge) badge.textContent = data.count;
        }
    } catch (e) {
        btn.style.pointerEvents = '';
        btn.innerHTML = '<i class="bi bi-heart-fill"></i>';
    }
}

// ── Add to cart ─────────────────────────────────────────
async function addToCart(productId, btn) {
    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Adding…';
    btn.style.pointerEvents = 'none';

    try {
        const res  = await fetch(CART_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        });
        const data = await res.json();

        btn.innerHTML = '<i class="bi bi-check-lg"></i> Added!';
        showToast('🛍️ Added to cart!', 'success');

        setTimeout(() => {
            btn.innerHTML = orig;
            btn.style.pointerEvents = '';
        }, 2000);

        // Update header cart count if present
        const cartBadge = document.querySelector('.cart-count');
        if (cartBadge && data.count) cartBadge.textContent = data.count;

    } catch (e) {
        btn.innerHTML = orig;
        btn.style.pointerEvents = '';
    }
}

// ── Clear all ───────────────────────────────────────────
document.getElementById('clearAllBtn')?.addEventListener('click', async function () {
    const cards = document.querySelectorAll('.wl-card');
    if (!cards.length) return;
    if (!confirm('Remove all ' + cards.length + ' items from your wishlist?')) return;

    for (const card of cards) {
        const id = card.dataset.id;
        await fetch(TOGGLE_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ product_id: id })
        });
        card.style.transition = 'all .25s';
        card.style.opacity = '0';
        card.style.transform = 'scale(.8)';
        await new Promise(r => setTimeout(r, 80));
    }

    setTimeout(() => {
        document.getElementById('wishlistGrid').innerHTML = `
            <div class="wl-empty" style="grid-column:1/-1;">
                <div class="wl-empty-icon"><i class="bi bi-heart"></i></div>
                <h3>Your wishlist is empty</h3>
                <p>Save items you love by tapping the heart icon on any product.</p>
                <a href="{{ route('products.index') }}" class="btn-shop-now">
                    <i class="bi bi-bag-heart"></i> Explore Collection
                </a>
            </div>`;
        document.querySelector('.wl-topbar')?.remove();
        updateCount(0);
    }, 400);
});

// ── Helpers ─────────────────────────────────────────────
function updateCount(n) {
    const remaining = n !== undefined ? n : document.querySelectorAll('.wl-card').length;
    const pill = document.querySelector('.wl-count-pill');
    if (pill) pill.innerHTML = `<i class="bi bi-heart-fill"></i> ${remaining} item${remaining !== 1 ? 's' : ''} saved`;
    const topbar = document.querySelector('.wl-topbar-left strong');
    if (topbar) topbar.textContent = remaining;
}

function showToast(msg, type = 'success') {
    const t = document.getElementById('wlToast');
    t.textContent = msg;
    t.className = 'wl-toast ' + type;
    t.style.display = 'block';
    clearTimeout(t._timer);
    t._timer = setTimeout(() => { t.style.display = 'none'; }, 3000);
}
</script>

@endsection
