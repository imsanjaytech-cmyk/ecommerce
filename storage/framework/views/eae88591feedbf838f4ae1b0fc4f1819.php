
<?php $__env->startSection('title','Product Details'); ?>

<?php $__env->startSection('content'); ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            <img src="https://source.unsplash.com/600x600/?gift,item" class="img-fluid rounded shadow">
        </div>

        <div class="col-md-6">
            <h3>Luxury Fancy Gift</h3>
            <h4 class="text-primary">₹1999</h4>
            <p>Beautiful premium quality gift item perfect for birthdays, anniversaries and celebrations.</p>

            <div class="d-flex gap-3 mt-3">
                <button class="btn btn-primary">Add to Cart</button>
                <button class="btn btn-outline-danger"><i class="fa fa-heart"></i></button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/pages/product.blade.php ENDPATH**/ ?>