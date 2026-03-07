
<?php $__env->startSection('title','Checkout'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:.82rem">
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('home')); ?>" style="color:var(--primary)">Home</a>
            </li>
            <li class="breadcrumb-item active">Checkout</li>
        </ol>
    </nav>

    <div class="row g-4">

        
        <div class="col-lg-7">
            <div class="checkout-box">
                <h5 class="fw-700 mb-4" style="font-size:1rem">
                    <i class="bi bi-person me-2" style="color:var(--primary)"></i>
                    Delivery Details
                </h5>

                <form method="POST" action="<?php echo e(route('place.order')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.8rem;font-weight:600">Full Name</label>
                            <input type="text" name="name"
                                   class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="e.g. Anita Sharma"
                                   value="<?php echo e(old('name')); ?>" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.8rem;font-weight:600">Email</label>
                            <input type="email" name="email"
                                   class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="your@email.com"
                                   value="<?php echo e(old('email')); ?>" required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.8rem;font-weight:600">Phone</label>
                            <input type="tel" name="phone"
                                   class="form-control"
                                   placeholder="+91 98765 43210"
                                   value="<?php echo e(old('phone')); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.8rem;font-weight:600">City</label>
                            <input type="text" name="city"
                                   class="form-control"
                                   placeholder="Mumbai"
                                   value="<?php echo e(old('city')); ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label" style="font-size:.8rem;font-weight:600">
                                Shipping Address
                            </label>
                            <textarea name="address" rows="3"
                                      class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      placeholder="House / Flat No., Street, Area, Pincode"
                                      required><?php echo e(old('address')); ?></textarea>
                            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-12">
                            <label class="form-label" style="font-size:.8rem;font-weight:600">
                                Gift Message <span style="color:var(--gray);font-weight:400">(optional)</span>
                            </label>
                            <textarea name="gift_message" rows="2"
                                      class="form-control"
                                      placeholder="Add a personal message to your gift..."><?php echo e(old('gift_message')); ?></textarea>
                        </div>

                        
                        <div class="col-12">
                            <label class="form-label" style="font-size:.8rem;font-weight:600">Payment Method</label>
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                <?php $__currentLoopData = [
                                    ['cod',   'bi-cash-coin',       'Cash on Delivery'],
                                    ['upi',   'bi-phone',           'UPI / GPay'],
                                    ['card',  'bi-credit-card',     'Card'],
                                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$val, $icon, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="pay-option">
                                    <input type="radio" name="payment" value="<?php echo e($val); ?>"
                                           <?php echo e($val === 'cod' ? 'checked' : ''); ?>>
                                    <span>
                                        <i class="bi <?php echo e($icon); ?>"></i> <?php echo e($label); ?>

                                    </span>
                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-4 py-3 fw-600">
                        <i class="bi bi-bag-check me-2"></i> Place Order
                    </button>

                </form>
            </div>
        </div>

        
        <div class="col-lg-5">
            <div class="checkout-box">
                <h5 class="fw-700 mb-4" style="font-size:1rem">
                    <i class="bi bi-bag me-2" style="color:var(--primary)"></i>
                    Order Summary
                </h5>

                <?php
                    $cart      = session('cart', []);
                    $cartTotal = session('cartTotal', 0);
                    $shipping  = $cartTotal >= 1500 ? 0 : 99;
                    $grandTotal = $cartTotal + $shipping;
                ?>

                <?php $__empty_1 = true; $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-center gap-3 mb-3 pb-3"
                     style="border-bottom:1px solid #f0f0f0">
                    <img src="<?php echo e($item['image']); ?>" alt="<?php echo e($item['name']); ?>"
                         style="width:54px;height:54px;border-radius:10px;object-fit:cover;flex-shrink:0">
                    <div class="flex-grow-1">
                        <div style="font-size:.85rem;font-weight:600"><?php echo e($item['name']); ?></div>
                        <div style="font-size:.78rem;color:var(--gray)">Qty: <?php echo e($item['qty']); ?></div>
                    </div>
                    <div style="font-size:.9rem;font-weight:700;color:var(--primary)">
                        ₹<?php echo e(number_format($item['price'] * $item['qty'])); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted text-center py-3" style="font-size:.85rem">
                        Your cart is empty.
                        <a href="<?php echo e(route('products.index')); ?>">Shop now</a>
                    </p>
                <?php endif; ?>

                
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>₹<?php echo e(number_format($cartTotal)); ?></span>
                </div>
                <div class="total-row">
                    <span>Shipping</span>
                    <span>
                        <?php if($shipping === 0): ?>
                            <span style="color:var(--success);font-weight:600">FREE</span>
                        <?php else: ?>
                            ₹<?php echo e($shipping); ?>

                        <?php endif; ?>
                    </span>
                </div>
                <?php if($shipping > 0): ?>
                <div class="total-row" style="font-size:.75rem;color:var(--gray);border:none;padding-top:0">
                    <span colspan="2">Add ₹<?php echo e(number_format(1500 - $cartTotal)); ?> more for free shipping</span>
                </div>
                <?php endif; ?>
                <div class="total-row total-final mt-2">
                    <span>Total</span>
                    <span>₹<?php echo e(number_format($grandTotal)); ?></span>
                </div>

                <div class="mt-3 d-flex flex-wrap gap-2" style="font-size:.72rem;color:var(--gray)">
                    <span><i class="bi bi-shield-check" style="color:var(--success)"></i> Secure Checkout</span>
                    <span><i class="bi bi-gift" style="color:var(--primary)"></i> Gift Wrapped</span>
                    <span><i class="bi bi-truck" style="color:var(--primary)"></i> Fast Delivery</span>
                </div>
            </div>
        </div>

    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
.pay-option input { display: none; }
.pay-option span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 50px;
    border: 1.5px solid var(--pink-border);
    font-size: .82rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    background: var(--pink-soft);
    color: var(--dark);
}
.pay-option input:checked + span {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/pages/checkout.blade.php ENDPATH**/ ?>