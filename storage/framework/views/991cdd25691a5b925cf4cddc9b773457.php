<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo e(url('/')); ?>">GiftStore</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(url('/')); ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(url('/shop')); ?>">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(url('/wishlist')); ?>">
                        <i class="fa fa-heart"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(url('/cart')); ?>">
                        <i class="fa fa-shopping-cart"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(url('/contact')); ?>">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/partials/navbar.blade.php ENDPATH**/ ?>