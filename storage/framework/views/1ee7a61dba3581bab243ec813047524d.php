
<?php $__env->startSection('title','Wishlist'); ?>

<?php $__env->startSection('content'); ?>

<div class="container py-5">
    <h3>Your Wishlist</h3>

    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <img src="https://source.unsplash.com/300x300/?gift" class="card-img-top">
                <div class="card-body text-center">
                    <h6>Romantic Gift</h6>
                    <button class="btn btn-primary btn-sm">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/pages/wishlist.blade.php ENDPATH**/ ?>