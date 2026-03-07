
<?php $__env->startSection('title','Shop'); ?>

<?php $__env->startSection('content'); ?>

<div class="container py-5">
    <h3 class="mb-4">All Products</h3>

    <div class="row">
        <?php for($i=1;$i<=12;$i++): ?>
        <div class="col-md-3 mb-4">
            <div class="card product-card shadow-sm">
                <img src="https://source.unsplash.com/300x300/?fancy,gift" class="card-img-top">
                <div class="card-body text-center">
                    <h6>Premium Fancy Gift</h6>
                    <p class="price">₹1499</p>
                    <button class="btn btn-primary btn-sm">Add to Cart</button>
                </div>
            </div>
        </div>
        <?php endfor; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/pages/shop.blade.php ENDPATH**/ ?>