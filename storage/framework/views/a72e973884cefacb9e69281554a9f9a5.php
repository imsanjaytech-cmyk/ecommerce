

<?php $__env->startSection('title', 'All Products — Shanas'); ?>

<?php $__env->startSection('content'); ?>

<div class="container py-5">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:.82rem">
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('home')); ?>" class="text-decoration-none" style="color:var(--primary)">Home</a>
            </li>
            <li class="breadcrumb-item active">Products</li>
        </ol>
    </nav>

    <div class="row g-4">

        
        <div class="col-lg-3">
            <div class="sticky-top" style="top:90px">
                <form method="GET" action="<?php echo e(route('products.index')); ?>" id="filterForm">

                    <input type="hidden" name="sort" value="<?php echo e(request('sort', 'popular')); ?>">

                    
                    <?php if(request('cat') || request('price') || request('occasion')): ?>
                    <div class="d-flex flex-wrap gap-1 mb-3">
                        <?php if(request('cat')): ?>
                            <span class="badge rounded-pill"
                                  style="background:var(--pink-soft);color:var(--primary);border:1px solid var(--pink-border);font-size:.7rem;font-weight:500">
                                <?php echo e($categories->firstWhere('slug', request('cat'))?->name ?? request('cat')); ?>

                                <a href="<?php echo e(route('products.index', array_merge(request()->except('cat'), ['sort' => request('sort','popular')]))); ?>"
                                   style="color:var(--primary);margin-left:3px">×</a>
                            </span>
                        <?php endif; ?>
                        <?php if(request('price')): ?>
                            <span class="badge rounded-pill"
                                  style="background:var(--pink-soft);color:var(--primary);border:1px solid var(--pink-border);font-size:.7rem;font-weight:500">
                                ₹ Range
                                <a href="<?php echo e(route('products.index', array_merge(request()->except('price'), ['sort' => request('sort','popular')]))); ?>"
                                   style="color:var(--primary);margin-left:3px">×</a>
                            </span>
                        <?php endif; ?>
                        <a href="<?php echo e(route('products.index')); ?>"
                           class="badge rounded-pill text-decoration-none"
                           style="background:#f8d7da;color:var(--danger);font-size:.7rem;font-weight:500">
                            Clear All ×
                        </a>
                    </div>
                    <?php endif; ?>

                    
                    <div class="filter-box mb-3">
                        <div class="filter-box-title">
                            <i class="bi bi-grid me-2" style="color:var(--primary)"></i>Category
                        </div>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input filter-radio" type="radio"
                                   name="cat" value="<?php echo e($cat->slug); ?>"
                                   id="cat_<?php echo e($cat->slug); ?>"
                                   <?php echo e(request('cat') === $cat->slug ? 'checked' : ''); ?>

                                   onchange="document.getElementById('filterForm').submit()">
                            <label class="form-check-label filter-label" for="cat_<?php echo e($cat->slug); ?>">
                                <?php echo e($cat->name); ?>

                                <?php if($cat->products_count): ?>
                                    <span style="color:var(--text-muted,#9199a6);font-size:.75rem;">(<?php echo e($cat->products_count); ?>)</span>
                                <?php endif; ?>
                            </label>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div class="filter-box mb-3">
                        <div class="filter-box-title">
                            <i class="bi bi-tag me-2" style="color:var(--primary)"></i>Price Range
                        </div>
                        <?php $__currentLoopData = [
                            [''         , 'All Prices'],
                            ['0-1000'   , 'Under ₹1,000'],
                            ['1000-3000', '₹1,000 – ₹3,000'],
                            ['3000-6000', '₹3,000 – ₹6,000'],
                            ['6000-0'   , 'Above ₹6,000'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$val, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input filter-radio" type="radio"
                                   name="price" value="<?php echo e($val); ?>"
                                   id="price_<?php echo e($val ?: 'all'); ?>"
                                   <?php echo e(request('price', '') === $val ? 'checked' : ''); ?>

                                   onchange="document.getElementById('filterForm').submit()">
                            <label class="form-check-label filter-label" for="price_<?php echo e($val ?: 'all'); ?>">
                                <?php echo e($label); ?>

                            </label>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div class="filter-box mb-3">
                        <div class="filter-box-title">
                            <i class="bi bi-balloon-heart me-2" style="color:var(--primary)"></i>Occasion
                        </div>
                        <?php $__currentLoopData = ['Birthday','Anniversary','Wedding','Festive','Corporate','Candles','Jewellery','Hampers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $occ): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox"
                                   name="occasion[]" value="<?php echo e(strtolower($occ)); ?>"
                                   id="occ_<?php echo e($occ); ?>"
                                   <?php echo e(in_array(strtolower($occ), (array) request('occasion', [])) ? 'checked' : ''); ?>>
                            <label class="form-check-label filter-label" for="occ_<?php echo e($occ); ?>">
                                <?php echo e($occ); ?>

                            </label>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <button type="submit" class="btn btn-primary btn-sm w-100 mt-2 rounded-pill">
                            Apply Filters
                        </button>
                    </div>

                </form>
            </div>
        </div>

        
        <div class="col-lg-9">

            
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                <span style="font-size:.85rem;color:var(--gray)">
                    Showing
                    <strong style="color:var(--primary)"><?php echo e($products->firstItem()); ?>–<?php echo e($products->lastItem()); ?></strong>
                    of
                    <strong><?php echo e($products->total()); ?></strong>
                    products
                    <?php if(request('cat')): ?>
                        in <strong><?php echo e($categories->firstWhere('slug', request('cat'))?->name ?? request('cat')); ?></strong>
                    <?php endif; ?>
                </span>

                <div class="d-flex align-items-center gap-2">
                    <label style="font-size:.82rem;color:var(--gray)">Sort by</label>
                    <select class="form-select form-select-sm rounded-pill"
                            style="width:auto;font-family:'Poppins',sans-serif;font-size:.82rem"
                            onchange="const url=new URL(window.location);url.searchParams.set('sort',this.value);window.location=url;">
                        <option value="popular"    <?php echo e(request('sort','popular') === 'popular'    ? 'selected' : ''); ?>>Most Popular</option>
                        <option value="newest"     <?php echo e(request('sort') === 'newest'               ? 'selected' : ''); ?>>Newest First</option>
                        <option value="price_asc"  <?php echo e(request('sort') === 'price_asc'            ? 'selected' : ''); ?>>Price: Low → High</option>
                        <option value="price_desc" <?php echo e(request('sort') === 'price_desc'           ? 'selected' : ''); ?>>Price: High → Low</option>
                        <option value="rating"     <?php echo e(request('sort') === 'rating'               ? 'selected' : ''); ?>>Top Rated</option>
                    </select>
                </div>
            </div>

            
            <div class="row g-3">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php echo $__env->make('components.product-card', compact('product'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12 text-center py-5">
                        <div style="font-size:3.5rem;opacity:.15;color:var(--primary)">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <p class="text-muted mt-3 mb-1" style="font-size:.95rem">No products found for your selection.</p>
                        <p class="text-muted mb-3" style="font-size:.82rem">Try changing your filters or browse all products.</p>
                        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Clear Filters
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            
            <?php if($products->hasPages()): ?>
            <div class="d-flex justify-content-center mt-5">
                <?php echo e($products->links('pagination::bootstrap-5')); ?>

            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
.filter-box {
    background: white;
    border-radius: var(--radius);
    border: 1.5px solid var(--pink-border);
    padding: 1rem 1.1rem;
}
.filter-box-title {
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: var(--dark);
    margin-bottom: .85rem;
    padding-bottom: .6rem;
    border-bottom: 1px solid var(--pink-border);
}
.filter-label {
    font-size: .85rem;
    cursor: pointer;
    color: var(--dark);
}
.filter-radio:checked + .filter-label {
    color: var(--primary);
    font-weight: 600;
}
.form-check-input:checked {
    background-color: var(--primary);
    border-color: var(--primary);
}
.form-check-input:focus {
    box-shadow: 0 0 0 .2rem rgba(255,77,109,.2);
    border-color: var(--primary);
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/pages/products.blade.php ENDPATH**/ ?>