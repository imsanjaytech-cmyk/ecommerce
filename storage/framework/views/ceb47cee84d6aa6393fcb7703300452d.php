

<?php $__env->startSection('title', 'Payment Failed'); ?>

<?php $__env->startSection('content'); ?>

<section class="py-5 text-center">
    <div class="container">
        <div class="card p-5 shadow-sm border-0">

            <div class="mb-4">
                <i class="fa fa-times-circle text-danger" style="font-size: 70px;"></i>
            </div>

            <h2 class="fw-bold mb-3 text-danger">Payment Failed ❌</h2>
            <p class="text-muted mb-4">
                Unfortunately, your payment could not be processed.
                Please try again.
            </p>

            <hr>

            <div class="text-start mx-auto" style="max-width: 400px;">
                <p><strong>Order ID:</strong> <?php echo e(session('order_id') ?? 'N/A'); ?></p>
                <p><strong>Payment ID:</strong> <?php echo e(session('payment_id') ?? 'N/A'); ?></p>
                <p><strong>Amount:</strong> ₹<?php echo e(session('amount') ?? '0'); ?></p>
                <p>
                    <strong>Status:</strong>
                    <span class="text-danger">Failed</span>
                </p>
                <p>
                    <strong>Reason:</strong><br>
                    <?php echo e(session('error_message') ?? 'Payment verification failed or was cancelled by user.'); ?>

                </p>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary">
                    Try Again
                </a>

                <a href="<?php echo e(route('home')); ?>" class="btn btn-outline-dark">
                    Back to Home
                </a>
            </div>

        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/pages/failed.blade.php ENDPATH**/ ?>