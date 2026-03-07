

<?php $__env->startSection('title', 'Shanas — Luxury Gifts & Fancy Items'); ?>

<?php $__env->startSection('content'); ?>


<section class="hero-section">
    <canvas id="heroCanvas"></canvas>
    <div class="hero-content">
        <p class="hero-eyebrow">Curated Luxury</p>
        <h1 class="hero-title">Gifts That<br><span class="hero-gradient">Leave a Mark</span></h1>
        <p class="hero-sub">Handpicked luxury items & bespoke gifts for every occasion</p>
        <div class="hero-actions">
            <a href="<?php echo e(route('products.index')); ?>" class="hero-btn-primary">Shop Now</a>
            <a href="<?php echo e(route('products.index', ['cat' => 'gift-sets'])); ?>" class="hero-btn-ghost">Gift Sets →</a>
        </div>
    </div>
</section>


<section class="categories-section">
    <div class="container">
        <div class="categories-scroll">
            <a href="<?php echo e(route('products.index')); ?>"
               class="cat-pill <?php echo e(! request('cat') ? 'active' : ''); ?>">
                All Gifts
            </a>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('products.index', ['cat' => $cat->slug])); ?>"
               class="cat-pill <?php echo e(request('cat') === $cat->slug ? 'active' : ''); ?>">
                <?php echo e($cat->name); ?>

                <?php if($cat->products_count): ?>
                    <span style="font-size:.65rem;opacity:.6;margin-left:3px;">(<?php echo e($cat->products_count); ?>)</span>
                <?php endif; ?>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<section class="products-section">
    <div class="container">

        <?php if($featuredProducts->count()): ?>
        <div class="section-header d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="section-tag">Handpicked For You</span>
                <h2 class="section-title mb-0">Featured Gifts</h2>
            </div>
            <a href="<?php echo e(route('products.index', ['featured' => 1])); ?>"
               class="btn-ghost-sm d-none d-md-inline-flex">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3">
            <?php $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('components.product-card', compact('product'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="text-center mt-4 d-md-none">
            <a href="<?php echo e(route('products.index', ['featured' => 1])); ?>" class="btn btn-outline-primary btn-sm px-4">
                View All Featured
            </a>
        </div>
        <?php else: ?>
        <div class="text-center py-5" style="color:var(--gray);">
            <i class="bi bi-box-seam" style="font-size:3rem;opacity:.25;"></i>
            <p class="mt-3 mb-3">No featured products yet.</p>
            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->role == 'admin'): ?>
                    <a href="<?php echo e(route('admin.products')); ?>" class="btn btn-primary btn-sm">
                        Add Products in Admin →
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</section>

<?php if($newArrivals->count()): ?>
<section class="products-section" style="padding-top:0;">
    <div class="container">
        <div class="section-header d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="section-tag">Just Landed</span>
                <h2 class="section-title mb-0">New Arrivals</h2>
            </div>
            <a href="<?php echo e(route('products.index', ['sort' => 'newest'])); ?>"
               class="btn-ghost-sm d-none d-md-inline-flex">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-3">
            <?php $__currentLoopData = $newArrivals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('components.product-card', compact('product'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>


<?php if($topSelling->count()): ?>
<section class="products-section" style="padding-top:0;">
    <div class="container">
        <div class="section-header d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="section-tag">Most Loved</span>
                <h2 class="section-title mb-0">Best Sellers</h2>
            </div>
            <a href="<?php echo e(route('products.index', ['sort' => 'rating'])); ?>"
               class="btn-ghost-sm d-none d-md-inline-flex">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-3">
            <?php $__currentLoopData = $topSelling; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('components.product-card', compact('product'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>


<section class="about-section">
    <div class="container">
        <div class="about-grid">

            <div class="about-visuals">
                <div class="about-img-card about-img-1">
                    <img src="https://images.unsplash.com/photo-1606800052052-a08af7148866?w=700&h=500&auto=format&fit=crop&q=80&crop=center"
                         alt="Our Story">
                </div>
                <div class="about-img-card about-img-2">
                    <img src="https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=400&h=300&auto=format&fit=crop&q=80&crop=center"
                         alt="Our Craft">
                </div>
                <div class="about-float-badge">
                    <span class="about-badge-num">5+</span>
                    <span class="about-badge-label">Years of<br>Luxury</span>
                </div>
                <div class="about-dots"></div>
            </div>

            <div class="about-text">
                <span class="section-tag">Who We Are</span>
                <h2 class="section-title">Crafted With Love,<br>Delivered With Care</h2>
                <p class="about-desc">
                    Shanas was born from a simple belief — that every gift should feel extraordinary.
                    We handpick every item in our collection, working with artisans and premium brands
                    to bring you pieces that speak louder than words.
                </p>

                <div class="about-stats">
                    <div class="about-stat">
                        <span class="about-stat-num">10K+</span>
                        <span class="about-stat-label">Happy Customers</span>
                    </div>
                    <div class="about-stat-divider"></div>
                    <div class="about-stat">
                        <span class="about-stat-num"><?php echo e($categories->sum('products_count')); ?>+</span>
                        <span class="about-stat-label">Unique Products</span>
                    </div>
                    <div class="about-stat-divider"></div>
                    <div class="about-stat">
                        <span class="about-stat-num">98%</span>
                        <span class="about-stat-label">Satisfaction Rate</span>
                    </div>
                </div>

                <div class="about-pillars">
                    <div class="pillar">
                        <div class="pillar-icon"><i class="bi bi-gem"></i></div>
                        <div>
                            <div class="pillar-title">Premium Quality</div>
                            <p class="pillar-desc">Every product is vetted for quality before it reaches you.</p>
                        </div>
                    </div>
                    <div class="pillar">
                        <div class="pillar-icon"><i class="bi bi-heart"></i></div>
                        <div>
                            <div class="pillar-title">Gifted with Intention</div>
                            <p class="pillar-desc">Thoughtfully curated for birthdays, anniversaries & beyond.</p>
                        </div>
                    </div>
                    <div class="pillar">
                        <div class="pillar-icon"><i class="bi bi-box-seam"></i></div>
                        <div>
                            <div class="pillar-title">Beautiful Packaging</div>
                            <p class="pillar-desc">Unboxing is part of the experience — every time.</p>
                        </div>
                    </div>
                </div>

                <a href="#" class="about-cta">
                    Discover Our Story <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>


<section class="services-section">
    <div class="container">
        <div class="row g-3">
            <?php $__currentLoopData = [
                ['bi-truck',          'Free Delivery',        'On orders above ₹999'],
                ['bi-arrow-repeat',   '30-Day Returns',       'Hassle-free return policy'],
                ['bi-shield-check',   'Secure Payments',      '100% safe & encrypted'],
                ['bi-gift',           'Gift Wrapping',        'Complimentary on every order'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon, $title, $desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-6 col-lg-3">
                <div class="service-card text-center">
                    <i class="bi <?php echo e($icon); ?> fs-3"></i>
                    <div class="service-title mt-2"><?php echo e($title); ?></div>
                    <p class="service-desc small mb-0"><?php echo e($desc); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="testimonials-section">
    <div class="container">
        <div class="text-center mb-4">
            <span class="section-tag">What Our Customers Say</span>
            <h2 class="section-title">Loved by Thousands</h2>
        </div>
        <div class="row g-3">
            <?php $__currentLoopData = [
                ['Priya S.',   5, 'The candle set was absolutely gorgeous. Arrived beautifully wrapped — my friend was thrilled!'],
                ['Rahul M.',   5, 'Ordered the hamper for my client. Premium quality, fast delivery. Will definitely order again.'],
                ['Anjali K.',  5, 'The personalised necklace exceeded expectations. Stunning packaging and engraving.'],
                ['Sameer D.',  4, 'Great quality jewellery set. The velvet gift box made it extra special for our anniversary.'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$name, $rating, $comment]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-6">
                <div class="testimonial-card">
                    <div class="testimonial-stars">
                        <?php echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating); ?>

                    </div>
                    <p>"<?php echo e($comment); ?>"</p>
                    <strong>— <?php echo e($name); ?></strong>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-inner text-center">
            <h2>Get 15% Off Your First Order</h2>
            <p style="opacity:.75;margin-bottom:24px;">Join 10,000+ happy customers. No spam, ever.</p>
            <form id="newsletterForm">
                <input type="email" name="email" class="newsletter-input"
                       placeholder="Your email address..." required>
                <button type="submit" class="newsletter-submit">Subscribe</button>
            </form>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('newsletterForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const email = this.querySelector('input[name="email"]').value;

    fetch("<?php echo e(route('newsletter.subscribe')); ?>", {
        method:  'POST',
        headers: {
            'Content-Type':  'application/json',
            'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ email }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('🎉 Subscribed! Check your inbox for your 15% code.');
            this.reset();
        }
    })
    .catch(() => showToast('Something went wrong. Please try again.'));
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/pages/home.blade.php ENDPATH**/ ?>