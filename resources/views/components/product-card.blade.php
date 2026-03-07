{{--
    components/product-card.blade.php
    Handles BOTH:
      - Eloquent  App\Models\Product  (home page: $featuredProducts, $newArrivals, etc.)
      - Plain array                   (products listing page from config)
--}}

@php
    $isModel = $product instanceof \App\Models\Product;

    $id          = $isModel ? $product->id           : ($product['id']           ?? 0);
    $slug        = $isModel ? $product->slug         : ($product['slug']         ?? '');
    $name        = $isModel ? $product->name         : ($product['name']         ?? '');
    $categoryName= $isModel ? ($product->category?->name ?? '') : ($product['category'] ?? '');
    $price       = $isModel ? (float)$product->active_price    : (float)($product['price']    ?? 0);
    $oldPrice    = $isModel
                    ? ($product->sale_price ? (float)$product->regular_price : null)
                    : (isset($product['old_price']) ? (float)$product['old_price'] : null);
    $rating      = $isModel ? (int)($product->rating      ?? 0) : (int)($product['rating']       ?? 0);
    $reviewCount = $isModel ? (int)($product->review_count ?? 0) : (int)($product['review_count'] ?? 0);
    $badge       = $isModel ? ($product->badge ?? null)          : ($product['badge']             ?? null);

    $discount = ($oldPrice && $oldPrice > $price)
                ? round((($oldPrice - $price) / $oldPrice) * 100)
                : 0;

    $placeholder = 'https://placehold.co/600x600/fff0f3/ff4d6d?text=No+Image';

    if ($isModel) {
        $imgUrl = $product->thumbnail_url;
    } else {
        $raw = $product['image'] ?? $product['thumbnail'] ?? '';

        if (empty($raw)) {
            $imgUrl = $placeholder;
        } elseif (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
            $imgUrl = $raw;
        } else {
            $clean  = ltrim(str_replace(['storage/', 'public/'], '', $raw), '/');
            $imgUrl = asset('storage/' . $clean);
        }
    }
@endphp

<div class="col-6 col-md-4 col-lg-3">
    <div class="product-card position-relative">

        {{-- ─── Image block ─── --}}
        <div class="position-relative overflow-hidden"
             style="border-radius:var(--radius) var(--radius) 0 0;">

            <a href="{{ route('products.show', $slug) }}">
                <img src="{{ $imgUrl }}"
                     class="w-100"
                     alt="{{ $name }}"
                     style="height:240px;object-fit:cover;transition:transform .5s ease;"
                     onerror="this.src='{{ $placeholder }}'"
                     loading="lazy">
            </a>

            {{-- Badge --}}
            @if($discount > 0)
                <span class="badge-sale">− {{ $discount }}%</span>
            @elseif($badge === 'new')
                <span class="badge-new">NEW</span>
            @elseif($badge === 'sale')
                <span class="badge-sale">SALE</span>
            @elseif($badge === 'bestseller')
                <span class="badge-sale" style="background:var(--dark);">★ BEST</span>
            @endif

            {{-- Wishlist button --}}
            <button class="wishlist-icon position-absolute"
                    style="top:10px;right:10px;"
                    onclick="toggleWishlist(this, {{ $id }})"
                    title="Add to Wishlist">
                <i class="bi bi-heart"></i>
            </button>

        </div>

        {{-- ─── Info block ─── --}}
        <div class="p-3">

            {{-- Category --}}
            @if($categoryName)
            <div class="text-muted mb-1"
                 style="font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;">
                {{ $categoryName }}
            </div>
            @endif

            {{-- Name --}}
            <a href="{{ route('products.show', $slug) }}"
               class="d-block fw-600 mb-1 text-dark text-decoration-none"
               style="font-size:.9rem;line-height:1.35;">
                {{ $name }}
            </a>

            {{-- Star rating --}}
            <div class="product-rating mb-2">
                @for($i = 1; $i <= 5; $i++)
                    {{ $i <= $rating ? '★' : '☆' }}
                @endfor
                <span>({{ $reviewCount }})</span>
            </div>

            {{-- Price --}}
            <div class="d-flex align-items-center gap-1 mb-2">
                <span class="price">₹{{ number_format($price) }}</span>
                @if($oldPrice)
                    <span class="price-old">₹{{ number_format($oldPrice) }}</span>
                @endif
            </div>

            {{-- Add to Cart --}}
            <button type="button"
                    class="btn btn-primary w-100 btn-sm"
                    onclick='addToCart({{ $id }}, @json($name))'>
                <i class="bi bi-bag-plus me-1"></i> Add to Cart
            </button>

        </div>
    </div>
</div>