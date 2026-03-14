@extends('layouts.app')
@section('title', 'My Wishlist — Shanas')

@section('content')

<style>
.wishlist-hero {
    background:#2558a0;
    padding: 44px 0 28px;
}
.wishlist-hero h1 {
    font-size: 2rem;
    font-weight: 800;
    color:#fff;
}
.wishlist-hero p { color: #fff; font-size: .88rem; }

/* Grid */
.wish-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
    gap: 20px;
    margin-top: 24px;
}

@media (max-width: 480px) {
    .wish-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
}

/* Card */
.wish-card {
    background: white;
    border-radius: 18px;
    border: 1.5px solid #eef0f4;
    overflow: hidden;
    transition: transform .25s, box-shadow .25s;
    position: relative;
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
}
.wish-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(255,77,109,.12);
}

.wish-img-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 1/1;
    overflow: hidden;
    background: #fafbfc;
}
.wish-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .4s ease;
}
.wish-card:hover .wish-img-wrap img { transform: scale(1.05); }

/* Remove heart button */
.wish-remove-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: white;
    border: 1.5px solid #eef0f4;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #ff4d6d;
    font-size: 14px;
    transition: all .2s;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
    z-index: 2;
}
.wish-remove-btn:hover {
    background: #ff4d6d;
    color: white;
    border-color: #ff4d6d;
    transform: scale(1.1);
}

/* Sale badge */
.wish-sale-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: linear-gradient(135deg, #ff4d6d, #e8304d);
    color: white;
    font-size: .65rem;
    font-weight: 800;
    padding: 3px 9px;
    border-radius: 6px;
    z-index: 2;
    letter-spacing: .03em;
}

.wish-body {
    padding: 14px 14px 16px;
}

.wish-cat {
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #ff8fab;
    margin-bottom: 4px;
}

.wish-name {
    font-size: .88rem;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.35;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.wish-price-row {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 12px;
}
.wish-price {
    font-size: 1rem;
    font-weight: 800;
    color: #ff4d6d;
}
.wish-price-old {
    font-size: .78rem;
    color: #c4c9d4;
    text-decoration: line-through;
    font-weight: 500;
}

.btn-add-to-cart {
    width: 100%;
    padding: 9px 14px;
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, #ff4d6d, #e8304d);
    color: white;
    font-size: .8rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    transition: all .2s;
    font-family: inherit;
    box-shadow: 0 3px 12px rgba(255,77,109,.25);
}
.btn-add-to-cart:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(255,77,109,.35);
}
.btn-add-to-cart.added {
    background: linear-gradient(135deg, #1f9c4a, #17803c);
    box-shadow: 0 3px 12px rgba(31,156,74,.25);
}

/* Stock badge */
.out-of-stock-overlay {
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,.75);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .78rem;
    font-weight: 700;
    color: #dc3545;
    backdrop-filter: blur(2px);
    z-index: 1;
}

/* Empty state */
.wishlist-empty {
    text-align: center;
    padding: 80px 20px;
    grid-column: 1 / -1;
}
.wishlist-empty .empty-heart {
    font-size: 4rem;
    opacity: .12;
    display: block;
    margin-bottom: 18px;
}
.wishlist-empty h4 { font-weight: 700; color: #1a1a1a; margin-bottom: 8px; }
.wishlist-empty p  { color: #9199a6; font-size: .88rem; margin-bottom: 24px; }
.btn-explore {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 26px;
    border-radius: 12px;
    background: linear-gradient(135deg, #ff4d6d, #e8304d);
    color: white;
    font-weight: 700;
    font-size: .85rem;
    text-decoration: none;
    box-shadow: 0 5px 18px rgba(255,77,109,.3);
    transition: all .2s;
}
.btn-explore:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(255,77,109,.4); color: white; }

/* Summary bar */
.wish-summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 4px;
}
.wish-count-txt {
    font-size: .82rem;
    color: #9199a6;
}
.wish-count-txt strong { color: #1a1a1a; }

.btn-clear-all {
    background: none;
    border: 1.5px solid #eef0f4;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: .78rem;
    font-weight: 600;
    color: #9199a6;
    cursor: pointer;
    font-family: inherit;
    transition: all .2s;
}
.btn-clear-all:hover { border-color: #dc3545; color: #dc3545; background: #fee8eb; }
</style>

{{-- ── Hero ── --}}
<div class="wishlist-hero">
    <div class="container">
        <h1><i class="bi bi-heart-fill me-2" style="color:#ff4d6d;font-size:1.5rem;vertical-align:middle;"></i>My Wishlist</h1>
        <p>Items you've saved to buy later</p>
    </div>
</div>

<div class="container py-4">

    @php
        $wishlist = session('wishlist', []);
        // $wishlistProducts = \App\Models\Product::whereIn('id', array_keys($wishlist))->with('category')->get();
        // Uncomment above and pass from controller. Below uses passed $products variable.
    @endphp

    @if(isset($products) && $products->count())

    {{-- Summary bar --}}
    <div class="wish-summary">
        <div class="wish-count-txt">
            <strong>{{ $products->count() }}</strong> item{{ $products->count() !== 1 ? 's' : '' }} saved
        </div>
        <!-- <button class="btn-clear-all" onclick="clearWishlist()">
            <i class="bi bi-trash me-1"></i> Clear All
        </button> -->
    </div>

    <div class="wish-grid">
        @foreach($products as $product)
        @php
            $salePrice    = $product->sale_price;
            $regularPrice = $product->regular_price;
            $activePrice  = $salePrice ?: $regularPrice;
            $hasSale      = $salePrice && $salePrice < $regularPrice;
            $discount     = $hasSale ? round((1 - $salePrice / $regularPrice) * 100) : 0;
            $outOfStock   = $product->stock_status === 'out_of_stock';
        @endphp

        <div class="wish-card" id="wish-card-{{ $product->id }}">

            {{-- Image --}}
            <div class="wish-img-wrap">
                @if($hasSale)
                <span class="wish-sale-badge">−{{ $discount }}%</span>
                @endif

                <button class="wish-remove-btn"
                        onclick="removeFromWishlist({{ $product->id }}, this)"
                        title="Remove from wishlist">
                    <i class="bi bi-heart-fill"></i>
                </button>

                <a href="{{ route('products.show', $product->slug) }}">
                    @if($product->thumbnail_url)
                    <img src="{{ $product->thumbnail_url }}"
                         alt="{{ $product->name }}"
                         loading="lazy"
                         onerror="this.src='{{ asset('images/placeholder.png') }}'">
                    @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#fff0f3;">
                        <i class="bi bi-gift" style="font-size:2.5rem;color:#ff8fab;opacity:.4;"></i>
                    </div>
                    @endif
                </a>

                @if($outOfStock)
                <div class="out-of-stock-overlay">Out of Stock</div>
                @endif
            </div>

            {{-- Body --}}
            <div class="wish-body">
                @if($product->category)
                <div class="wish-cat">{{ $product->category->name }}</div>
                @endif

                <a href="{{ route('products.show', $product->slug) }}" style="text-decoration:none;">
                    <div class="wish-name">{{ $product->name }}</div>
                </a>

                <div class="wish-price-row">
                    <span class="wish-price">₹{{ number_format($activePrice) }}</span>
                    @if($hasSale)
                    <span class="wish-price-old">₹{{ number_format($regularPrice) }}</span>
                    @endif
                </div>

                <button class="btn-add-to-cart {{ $outOfStock ? 'opacity-50' : '' }}"
                        id="atc-{{ $product->id }}"
                        onclick="addToCartFromWishlist({{ $product->id }}, this)"
                        {{ $outOfStock ? 'disabled' : '' }}>
                    <i class="bi bi-bag-plus"></i>
                    {{ $outOfStock ? 'Out of Stock' : 'Add to Cart' }}
                </button>
            </div>
        </div>
        @endforeach
    </div>

    @else

    {{-- Empty --}}
    <div class="wish-grid">
        <div class="wishlist-empty">
            <span class="empty-heart">🤍</span>
            <h4>Your wishlist is empty</h4>
            <p>Browse our collection and save the items you love.<br>They'll be right here waiting for you.</p>
            <a href="{{ route('products.index') }}" class="btn-explore">
                Explore Products <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    @endif
</div>

<script>
function removeFromWishlist(productId, btn) {
    const card = document.getElementById('wish-card-' + productId);
    card.style.transition = 'opacity .3s, transform .3s';
    card.style.opacity    = '0';
    card.style.transform  = 'scale(.92)';

    fetch('{{ route("wishlist.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ product_id: productId }),
    })
    .then(r => r.json())
    .then(() => {
        setTimeout(() => {
            card.remove();
            // Update count
            const remaining = document.querySelectorAll('.wish-card').length;
            const countEl = document.querySelector('.wish-count-txt strong');
            if (countEl) countEl.textContent = remaining;
            if (remaining === 0) location.reload();
        }, 320);
    })
    .catch(() => {
        card.style.opacity   = '1';
        card.style.transform = '';
    });
}

function addToCartFromWishlist(productId, btn) {
    btn.disabled = true;
    const original = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ product_id: productId, qty: 1 }),
    })
    .then(r => r.json())
    .then(data => {
        btn.classList.add('added');
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Added!';
        // Update cart count in navbar if you have one
        const cartBadge = document.getElementById('cartCount');
        if (cartBadge && data.cart_count) cartBadge.textContent = data.cart_count;
        setTimeout(() => {
            btn.classList.remove('added');
            btn.innerHTML = original;
            btn.disabled  = false;
        }, 2000);
    })
    .catch(() => {
        btn.innerHTML = original;
        btn.disabled  = false;
    });
}

function clearWishlist() {
    if (!confirm('Remove all items from your wishlist?')) return;
    document.querySelectorAll('.wish-card').forEach(card => {
        const id = card.id.replace('wish-card-', '');
        card.style.opacity   = '0';
        card.style.transform = 'scale(.9)';
        card.style.transition= 'all .3s';
    });
    setTimeout(() => location.reload(), 400);
}

function toggleWishlist(btn, productId) {
    const icon = btn.querySelector('i');
    const isWishlisted = icon.classList.contains('bi-heart-fill');

    fetch('{{ route("wishlist.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ product_id: productId }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.added) {
            icon.classList.replace('bi-heart', 'bi-heart-fill');
            btn.style.color = '#ff4d6d';
        } else {
            icon.classList.replace('bi-heart-fill', 'bi-heart');
            btn.style.color = '';
        }
    });
}
</script>

@endsection
