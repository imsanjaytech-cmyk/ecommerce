

<?php $__env->startSection('title', $product->name . ' — Shanas'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:.82rem;">
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('home')); ?>" style="color:var(--primary);">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('products.index')); ?>" style="color:var(--primary);">Products</a>
            </li>
            <?php if($product->category): ?>
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('products.index', ['cat' => $product->category->slug])); ?>" style="color:var(--primary);">
                    <?php echo e($product->category->name); ?>

                </a>
            </li>
            <?php endif; ?>
            <li class="breadcrumb-item active"><?php echo e($product->name); ?></li>
        </ol>
    </nav>

    <div class="row g-5">

        <div class="col-lg-6">
            <div class="position-sticky" style="top:90px;">

                <div class="rounded-4 overflow-hidden mb-3" style="aspect-ratio:1;">
                    <img id="mainImg"
                         src="<?php echo e($product->thumbnail_url); ?>"
                         alt="<?php echo e($product->name); ?>"
                         class="w-100 h-100"
                         style="object-fit:cover;transition:var(--transition);"
                         onerror="this.src='https://placehold.co/600x600/fff0f3/ff4d6d?text=No+Image'">
                </div>

                <?php $gallery = $product->gallery_urls; ?>
                <?php if(count($gallery) > 1): ?>
                <div class="d-flex gap-2" style="flex-wrap:wrap;">
                    <?php $__currentLoopData = $gallery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $imgUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="rounded-3 overflow-hidden"
                         style="width:70px;height:70px;cursor:pointer;flex-shrink:0;
                                border:2px solid <?php echo e($i === 0 ? 'var(--primary)' : 'var(--border-col,#eef0f4)'); ?>;
                                transition:border-color .2s;"
                         onclick="switchImage(this, '<?php echo e($imgUrl); ?>')">
                        <img src="<?php echo e($imgUrl); ?>" alt="" class="w-100 h-100" style="object-fit:cover;">
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="col-lg-6">

            <div class="d-flex align-items-center gap-2 mb-2">
                <?php if($product->category): ?>
                <span class="section-tag"><?php echo e($product->category->name); ?></span>
                <?php endif; ?>
                <?php if($product->badge === 'bestseller'): ?>
                    <span class="badge rounded-pill" style="background:var(--dark);color:#fff;font-size:.7rem;">★ Bestseller</span>
                <?php elseif($product->badge === 'new'): ?>
                    <span class="badge rounded-pill" style="background:var(--primary);color:#fff;font-size:.7rem;">New</span>
                <?php elseif($product->badge === 'sale'): ?>
                    <span class="badge rounded-pill" style="background:var(--danger);color:#fff;font-size:.7rem;">Sale</span>
                <?php endif; ?>
            </div>

            <h1 style="font-size:1.9rem;font-weight:700;line-height:1.25;letter-spacing:-.3px;">
                <?php echo e($product->name); ?>

            </h1>

            <div class="d-flex align-items-center gap-2 my-3">
                <span style="color:#ffc107;letter-spacing:1px;">
                    <?php for($i = 1; $i <= 5; $i++): ?><?php echo e($i <= $product->rating ? '★' : '☆'); ?><?php endfor; ?>
                </span>
                <span style="font-size:.82rem;color:var(--gray);">
                    <?php echo e($product->rating); ?>/5
                    <?php if($product->review_count): ?>(<?php echo e($product->review_count); ?> reviews)<?php endif; ?>
                </span>
            </div>

            <div class="d-flex align-items-center gap-3 mb-4">
                <span style="font-size:2rem;font-weight:700;color:var(--primary);">
                    ₹<?php echo e(number_format($product->active_price)); ?>

                </span>
                <?php if($product->sale_price): ?>
                <span style="font-size:1.1rem;text-decoration:line-through;color:var(--gray);">
                    ₹<?php echo e(number_format($product->regular_price)); ?>

                </span>
                <span class="badge rounded-pill" style="background:var(--danger);font-size:.8rem;">
                    <?php echo e($product->discount_percent); ?>% OFF
                </span>
                <?php endif; ?>
            </div>

            <p style="font-size:.92rem;color:var(--gray);line-height:1.8;" class="mb-4">
                <?php echo e($product->short_description ?? $product->description); ?>

            </p>

            <?php if($product->tags_array): ?>
            <div class="d-flex flex-wrap gap-2 mb-4">
                <?php $__currentLoopData = $product->tags_array; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('products.index', ['occasion' => $tag])); ?>"
                   style="font-size:.72rem;font-weight:600;background:var(--pink-soft);color:var(--primary);
                          padding:3px 10px;border-radius:20px;text-decoration:none;border:1px solid var(--pink-border);">
                    <?php echo e(ucfirst($tag)); ?>

                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>

            <div class="mb-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <label style="font-size:.82rem;font-weight:600;">Quantity</label>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                        <input type="number" id="qtyInput" value="1"
                               min="1" max="<?php echo e($product->stock_quantity ?: 99); ?>"
                               class="form-control text-center fw-600"
                               style="width:60px;border-color:var(--pink-border);">
                        <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                    </div>
                    <?php if($product->stock_status === 'low_stock'): ?>
                    <span style="font-size:.75rem;color:#d97706;font-weight:600;">
                        <i class="bi bi-exclamation-triangle"></i> Only <?php echo e($product->stock_quantity); ?> left
                    </span>
                    <?php elseif($product->stock_status === 'out_of_stock'): ?>
                    <span style="font-size:.75rem;color:var(--danger);font-weight:600;">
                        <i class="bi bi-x-circle"></i> Out of Stock
                    </span>
                    <?php endif; ?>
                </div>

                
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button"
                            id="addToCartBtn"
                            class="btn btn-primary btn-lg px-5"
                            <?php echo e($product->stock_status === 'out_of_stock' ? 'disabled' : ''); ?>

                            data-id="<?php echo e($product->id); ?>"
                            data-name="<?php echo e($product->name); ?>"
                            onclick="addToCart(this.dataset.id, this.dataset.name, document.getElementById('qtyInput').value)">
                        <i class="bi bi-bag-plus me-2"></i>
                        <?php echo e($product->stock_status === 'out_of_stock' ? 'Out of Stock' : 'Add to Cart'); ?>

                    </button>
                    <button type="button"
                            class="wishlist-icon"
                            style="width:50px;height:50px;font-size:1.2rem;"
                            onclick="toggleWishlist(this, <?php echo e($product->id); ?>)">
                        <i class="bi bi-heart"></i>
                    </button>
                </div>
            </div>

            <div class="p-3 rounded-3 mb-4"
                 style="background:var(--pink-soft);border:1.5px dashed var(--secondary);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span>🎁</span>
                    <strong style="font-size:.88rem;">Add a Gift Note</strong>
                </div>
                <input type="text" class="form-control"
                       placeholder="Write your personalised message here..."
                       style="border-color:var(--pink-border);font-size:.85rem;">
            </div>

            <div class="row g-2 mb-4">
                <?php $__currentLoopData = [
                    ['bi-truck',           'Free Delivery on this item'],
                    ['bi-gift',            'Gift Wrapping Included'],
                    ['bi-arrow-clockwise', '30-Day Returns'],
                    ['bi-shield-check',    'Secure Payment'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12">
                    <div class="d-flex align-items-center gap-2" style="font-size:.82rem;color:var(--gray);">
                        <i class="bi <?php echo e($icon); ?>" style="color:var(--success);"></i>
                        <?php echo e($label); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="d-flex align-items-center gap-2 pt-4 border-top">
                <span style="font-size:.78rem;color:var(--gray);text-transform:uppercase;letter-spacing:.08em;">Share</span>
                <a href="https://wa.me/?text=<?php echo e(urlencode($product->name . ' — ' . route('products.show', $product->slug))); ?>"
                   target="_blank" class="social-icon" title="Share on WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(route('products.show', $product->slug))); ?>"
                   target="_blank" class="social-icon" title="Share on Facebook">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="#" class="social-icon"
                   onclick="navigator.clipboard.writeText('<?php echo e(route('products.show', $product->slug)); ?>');showToast('Link copied!');"
                   title="Copy link">
                    <i class="bi bi-link-45deg"></i>
                </a>
            </div>

        </div>
    </div>

    <?php if($related->count()): ?>
    <div class="mt-6 pt-5 border-top">
        <div class="text-center mb-4">
            <span class="section-tag">You Might Also Like</span>
            <h3 class="section-title">More from <?php echo e($product->category?->name ?? 'Our Collection'); ?></h3>
        </div>
        <div class="row g-3">
            <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('components.product-card', ['product' => $relProduct], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function changeQty(delta) {
    const inp = document.getElementById('qtyInput');
    inp.value = Math.max(1, parseInt(inp.value) + delta);
}

function switchImage(thumb, url) {
    document.getElementById('mainImg').src = url;
    document.querySelectorAll('[onclick^="switchImage"]').forEach(el => {
        el.style.borderColor = 'var(--border-col, #eef0f4)';
    });
    thumb.style.borderColor = 'var(--primary)';
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/pages/product-detail.blade.php ENDPATH**/ ?>