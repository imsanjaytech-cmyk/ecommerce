<?php $cart = session('cart', []); ?>

<?php if(count($cart) === 0): ?>
    <div class="text-center py-5">
        <div style="font-size:4rem;opacity:.2">🛍️</div>
        <p class="text-muted mt-3">Your cart is empty</p>
        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary rounded-pill px-4 mt-2"
           data-bs-dismiss="offcanvas">Start Shopping</a>
    </div>
<?php else: ?>
    <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="cart-item-row" id="cart-row-<?php echo e($id); ?>">
        <img class="cart-item-img"
             src="<?php echo e($item['image']); ?>"
             alt="<?php echo e($item['name']); ?>">
        <div class="flex-grow-1">
            <div class="cart-item-name"><?php echo e($item['name']); ?></div>
            <div class="cart-item-price">₹<?php echo e(number_format($item['price'])); ?></div>
            <div class="qty-control">
                <button class="qty-btn" onclick="updateQty(<?php echo e($id); ?>, -1)">−</button>
                <span class="qty-num" id="qty-<?php echo e($id); ?>"><?php echo e($item['qty']); ?></span>
                <button class="qty-btn" onclick="updateQty(<?php echo e($id); ?>, 1)">+</button>
            </div>
        </div>
        <button class="btn-close btn-sm ms-2" onclick="removeFromCart(<?php echo e($id); ?>)"></button>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/components/cart-items.blade.php ENDPATH**/ ?>