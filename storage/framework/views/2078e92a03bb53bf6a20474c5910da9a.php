
<?php $__env->startSection('page-title', 'Customers'); ?>
<?php $__env->startSection('breadcrumb', 'Home / Customers'); ?>

<?php $__env->startSection('content'); ?>


<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-deco" style="background:#1a7cd4;"></div>
            <div class="stat-icon si-blue"><i class="bi bi-people-fill"></i></div>
            <div class="stat-label">Total Customers</div>
            <div class="stat-value"><?php echo e(number_format($counts['customer'])); ?></div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> Registered users</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-deco" style="background:var(--primary);"></div>
            <div class="stat-icon si-pink"><i class="bi bi-shop"></i></div>
            <div class="stat-label">Vendors</div>
            <div class="stat-value"><?php echo e(number_format($counts['vendor'])); ?></div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> Active vendors</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-deco" style="background:#d97706;"></div>
            <div class="stat-icon si-orange"><i class="bi bi-shield-lock-fill"></i></div>
            <div class="stat-label">Admins</div>
            <div class="stat-value"><?php echo e(number_format($counts['admin'])); ?></div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> System admins</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-deco" style="background:#1f9c4a;"></div>
            <div class="stat-icon si-green"><i class="bi bi-person-fill-add"></i></div>
            <div class="stat-label">New This Month</div>
            <div class="stat-value"><?php echo e(number_format($newThisMonth)); ?></div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> All roles</div>
        </div>
    </div>
</div>

<div class="card-w">

    
    <div class="sec-header flex-wrap gap-2">
        <div>
            <div class="sec-title">User Management</div>
            <div class="sec-sub"><?php echo e(number_format($users->total())); ?> <?php echo e(ucfirst($role)); ?>s found</div>
        </div>
        <form method="GET" action="<?php echo e(route('admin.customers')); ?>" style="display:flex;gap:8px;">
            <input type="hidden" name="role" value="<?php echo e($role); ?>">
            <div class="search-wrap" style="width:220px;">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Search name or email...">
            </div>
            <button type="submit" class="btn-o"><i class="bi bi-search"></i></button>
        </form>
    </div>

    
    <div style="display:flex;gap:6px;margin-bottom:18px;border-bottom:1.5px solid var(--border-col);padding-bottom:0;">
        <?php $__currentLoopData = ['customer' => 'Customers', 'vendor' => 'Vendors', 'admin' => 'Admins']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('admin.customers', ['role' => $r])); ?>"
           style="padding:8px 18px;font-size:13px;font-weight:600;border-radius:8px 8px 0 0;
                  text-decoration:none;border:1.5px solid transparent;margin-bottom:-1.5px;
                  <?php echo e($role === $r
                      ? 'background:white;border-color:var(--border-col);border-bottom-color:white;color:var(--primary);'
                      : 'color:var(--text-muted);'); ?>">
            <?php echo e($label); ?>

            <span style="font-size:11px;margin-left:4px;opacity:.7;">(<?php echo e(number_format($counts[$r])); ?>)</span>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:10px;
                                        background:linear-gradient(135deg,var(--primary),var(--secondary));
                                        display:flex;align-items:center;justify-content:center;
                                        font-size:13px;font-weight:700;color:white;flex-shrink:0;">
                                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13.5px;"><?php echo e($user->name); ?></div>
                                <div style="font-size:11px;color:var(--text-muted);">ID #<?php echo e($user->id); ?></div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;color:var(--text-muted);"><?php echo e($user->email); ?></td>
                    <td>
                        <?php if($user->role === 'admin'): ?>
                            <span class="bdg bdg-danger">Admin</span>
                        <?php elseif($user->role === 'vendor'): ?>
                            <span class="bdg bdg-info">Vendor</span>
                        <?php else: ?>
                            <span class="bdg bdg-gray">Customer</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:12.5px;color:var(--text-muted);">
                        <?php echo e($user->created_at->format('M d, Y')); ?>

                    </td>
                    <td>
                        <?php if($user->email_verified_at): ?>
                            <span class="bdg bdg-success">Verified</span>
                        <?php else: ?>
                            <span class="bdg bdg-warning">Unverified</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="act-row">
                            <div class="act-btn" title="View"><i class="bi bi-eye"></i></div>
                            <div class="act-btn" title="Email"><i class="bi bi-envelope"></i></div>
                            <?php if($user->id !== auth()->id()): ?>
                            <form method="POST" action="<?php echo e(route('admin.customers.destroy', $user)); ?>"
                                  onsubmit="return confirm('Delete <?php echo e(addslashes($user->name)); ?>?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="act-btn del" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="text-align:center;padding:50px;color:var(--text-muted);">
                        <i class="bi bi-people" style="font-size:32px;opacity:.25;display:block;margin-bottom:10px;"></i>
                        No <?php echo e($role); ?>s found
                        <?php if($search): ?> for "<strong><?php echo e($search); ?></strong>" <?php endif; ?>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if($users->hasPages()): ?>
    <div class="pgn">
        <span class="pgn-info">
            Showing <?php echo e($users->firstItem()); ?>–<?php echo e($users->lastItem()); ?>

            of <?php echo e(number_format($users->total())); ?> <?php echo e($role); ?>s
        </span>
        <div class="pgn-btns">
            <?php if($users->onFirstPage()): ?>
                <div class="pgn-btn" style="opacity:.4;pointer-events:none;">
                    <i class="bi bi-chevron-left" style="font-size:10px;"></i>
                </div>
            <?php else: ?>
                <a href="<?php echo e($users->previousPageUrl()); ?>" class="pgn-btn" style="text-decoration:none;">
                    <i class="bi bi-chevron-left" style="font-size:10px;"></i>
                </a>
            <?php endif; ?>

            <?php $__currentLoopData = $users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($url); ?>" class="pgn-btn <?php echo e($page === $users->currentPage() ? 'active' : ''); ?>" style="text-decoration:none;">
                    <?php echo e($page); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if($users->hasMorePages()): ?>
                <a href="<?php echo e($users->nextPageUrl()); ?>" class="pgn-btn" style="text-decoration:none;">
                    <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                </a>
            <?php else: ?>
                <div class="pgn-btn" style="opacity:.4;pointer-events:none;">
                    <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/admin/customers.blade.php ENDPATH**/ ?>