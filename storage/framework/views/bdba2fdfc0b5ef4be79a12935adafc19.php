

<?php
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
?>

<div class="col-6 col-md-4 col-lg-3">
    <div class="product-card position-relative">

        
        <div class="position-relative overflow-hidden"
             style="border-radius:var(--radius) var(--radius) 0 0;">

            <a href="<?php echo e(route('products.show', $slug)); ?>">
                <img src="<?php echo e($imgUrl); ?>"
                     class="w-100"
                     alt="<?php echo e($name); ?>"
                     style="height:240px;object-fit:cover;transition:transform .5s ease;"
                     onerror="this.src='<?php echo e($placeholder); ?>'"
                     loading="lazy">
            </a>

            
            <?php if($discount > 0): ?>
                <span class="badge-sale">− <?php echo e($discount); ?>%</span>
            <?php elseif($badge === 'new'): ?>
                <span class="badge-new">NEW</span>
            <?php elseif($badge === 'sale'): ?>
                <span class="badge-sale">SALE</span>
            <?php elseif($badge === 'bestseller'): ?>
                <span class="badge-sale" style="background:var(--dark);">★ BEST</span>
            <?php endif; ?>

            
            <button class="wishlist-icon position-absolute"
                    style="top:10px;right:10px;"
                    onclick="toggleWishlist(this, <?php echo e($id); ?>)"
                    title="Add to Wishlist">
                <i class="bi bi-heart"></i>
            </button>

        </div>

        
        <div class="p-3">

            
            <?php if($categoryName): ?>
            <div class="text-muted mb-1"
                 style="font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;">
                <?php echo e($categoryName); ?>

            </div>
            <?php endif; ?>

            
            <a href="<?php echo e(route('products.show', $slug)); ?>"
               class="d-block fw-600 mb-1 text-dark text-decoration-none"
               style="font-size:.9rem;line-height:1.35;">
                <?php echo e($name); ?>

            </a>

            
            <div class="product-rating mb-2">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <?php echo e($i <= $rating ? '★' : '☆'); ?>

                <?php endfor; ?>
                <span>(<?php echo e($reviewCount); ?>)</span>
            </div>

            
            <div class="d-flex align-items-center gap-1 mb-2">
                <span class="price">₹<?php echo e(number_format($price)); ?></span>
                <?php if($oldPrice): ?>
                    <span class="price-old">₹<?php echo e(number_format($oldPrice)); ?></span>
                <?php endif; ?>
            </div>

            
            <button type="button"
                    class="btn btn-primary w-100 btn-sm"
                    onclick='addToCart(<?php echo e($id); ?>, <?php echo json_encode($name, 15, 512) ?>)'>
                <i class="bi bi-bag-plus me-1"></i> Add to Cart
            </button>

        </div>
    </div>
</div><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/components/product-card.blade.php ENDPATH**/ ?>