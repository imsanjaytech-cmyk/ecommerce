

<?php $__env->startSection('content'); ?>
<h1>My Orders</h1>

<?php if(!$orders): ?>
    <p>You have no orders yet.</p>
<?php else: ?>
    <ul>
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <a href="<?php echo e(route('account.order.detail', $order)); ?>">
                    Order #<?php echo e($order->id); ?> — <?php echo e($order->status); ?>

                </a>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/account/orders.blade.php ENDPATH**/ ?>