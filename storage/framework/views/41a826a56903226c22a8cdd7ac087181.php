

<?php $__env->startSection('title', 'Your Cart — Shanas'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <h1 class="section-title mb-1">Your Cart</h1>
    <p class="text-muted mb-4" style="font-size:.88rem">
        <?php echo e(count(session('cart', []))); ?> item(s) in your bag
    </p>

    <?php if(count(session('cart', [])) === 0): ?>
    <div class="text-center py-6" style="padding:5rem 0">
        <div style="font-size:5rem;opacity:.15">🛍️</div>
        <h3 class="mt-4 mb-2">Your cart is empty</h3>
        <p class="text-muted">Start adding some beautiful gifts!</p>
        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary rounded-pill px-5 mt-3">
            Shop Now
        </a>
    </div>

    <?php else: ?>
    <div class="row g-4">
        <!-- Cart Items -->
        <div class="col-lg-8">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = session('cart', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr id="row-<?php echo e($id); ?>">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?php echo e($item['image']); ?>" alt="<?php echo e($item['name']); ?>"
                                         class="rounded-3" style="width:65px;height:65px;object-fit:cover">
                                    <div>
                                        <div class="fw-600" style="font-size:.9rem"><?php echo e($item['name']); ?></div>
                                        <div style="font-size:.75rem;color:var(--gray)"><?php echo e($item['category'] ?? ''); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-600" style="color:var(--primary)">
                                ₹<?php echo e(number_format($item['price'])); ?>

                            </td>
                            <td>
                                <div class="qty-control">
                                    <button class="qty-btn" onclick="updateCartQty(<?php echo e($id); ?>, -1)">−</button>
                                    <span class="qty-num" id="qty-<?php echo e($id); ?>"><?php echo e($item['qty']); ?></span>
                                    <button class="qty-btn" onclick="updateCartQty(<?php echo e($id); ?>, 1)">+</button>
                                </div>
                            </td>
                            <td class="fw-700">
                                ₹<span id="subtotal-<?php echo e($id); ?>"><?php echo e(number_format($item['price'] * $item['qty'])); ?></span>
                            </td>
                            <td>
                                <button class="btn-close" onclick="removeCartItem(<?php echo e($id); ?>)"></button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Coupon -->
            <div class="p-4 bg-white rounded-3 border mt-3" style="border-color:var(--pink-border)!important">
                <h6 class="fw-600 mb-3">Have a Coupon Code?</h6>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" placeholder="Enter coupon code"
                           style="max-width:250px">
                    <button class="btn btn-outline-primary rounded-pill px-4">Apply</button>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="checkout-box">
                <h5 class="fw-700 mb-4">Order Summary</h5>

                <div class="total-row">
                    <span>Subtotal</span>
                    <span>₹<?php echo e(number_format(session('cartTotal', 0))); ?></span>
                </div>
                <div class="total-row">
                    <span>Delivery</span>
                    <span class="text-success"><?php echo e(session('cartTotal', 0) >= 1500 ? 'FREE' : '₹99'); ?></span>
                </div>
                <div class="total-row">
                    <span>Gift Wrapping</span>
                    <span class="text-success">FREE</span>
                </div>
                <div class="total-row total-final pt-3 border-top mt-1">
                    <span>Total</span>
                    <span>₹<?php echo e(number_format(session('cartTotal', 0) + (session('cartTotal', 0) >= 1500 ? 0 : 99))); ?></span>
                </div>

                <a href="<?php echo e(route('checkout')); ?>" class="btn btn-primary w-100 py-3 mt-4">
                    Proceed to Checkout <i class="bi bi-arrow-right ms-1"></i>
                </a>
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary w-100 mt-2">
                    Continue Shopping
                </a>

                <!-- Reach options -->
                <div class="mt-4 pt-4 border-top">
                    <p class="text-muted mb-2" style="font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em">
                        Need Help?
                    </p>
                    <div class="d-flex flex-column gap-2">
                        <a href="https://wa.me/918001234567" class="btn btn-outline-success btn-sm rounded-pill">
                            <i class="bi bi-whatsapp me-1"></i> WhatsApp Us
                        </a>
                        <a href="tel:+918001234567" class="btn btn-outline-secondary btn-sm rounded-pill">
                            <i class="bi bi-telephone me-1"></i> Call +91 800 123 4567
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/pages/cart.blade.php ENDPATH**/ ?>