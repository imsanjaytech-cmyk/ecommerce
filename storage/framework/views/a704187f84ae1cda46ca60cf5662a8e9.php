

<?php $__env->startSection('content'); ?>
<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow rounded-4 border-0">
                <div class="card-body p-4">

                    <h4 class="mb-4 fw-bold">My Profile</h4>

                    <form method="POST" action="<?php echo e(route('account.profile.update')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text"
                                   name="name"
                                   class="form-control rounded-3"
                                   value="<?php echo e(auth()->user()->name); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email"
                                   class="form-control rounded-3"
                                   value="<?php echo e(auth()->user()->email); ?>"
                                   disabled>
                        </div>

                        <button class="btn btn-primary rounded-pill px-4">
                            Update Profile
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/account/profile.blade.php ENDPATH**/ ?>