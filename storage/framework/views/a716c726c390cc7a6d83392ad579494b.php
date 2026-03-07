
<?php $__env->startSection('page-title', 'Categories'); ?>
<?php $__env->startSection('breadcrumb', 'Home / Categories'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <p style="font-size:13px;color:var(--text-muted);margin:0;">Manage product categories and subcategories</p>
    <button class="btn-p" data-bs-toggle="modal" data-bs-target="#addCatModal">
        <i class="bi bi-plus-lg"></i> Add Category
    </button>
</div>

<div class="row g-3">

    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-md-4 col-sm-6">
        <div class="card-w" style="border-left:4px solid <?php echo e($c['color'] ?? '#000'); ?>;">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:46px;height:46px;border-radius:12px;background:<?php echo e($c['color'] ?? '#000'); ?>18;border:1.5px solid <?php echo e($c['color'] ?? '#000'); ?>22;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi <?php echo e($c['icon'] ?? 'bi-tag'); ?>" style="font-size:18px;color:<?php echo e($c['color'] ?? '#000'); ?>;"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:14.5px;color:var(--dark);"><?php echo e($c['name'] ?? '—'); ?></div>
                        <div style="font-size:11px;color:var(--text-muted);">/<?php echo e($c['slug'] ?? '—'); ?></div>
                    </div>
                </div>
                <div style="display:flex;gap:5px;">
                    <div class="act-btn" style="opacity:1;"><i class="bi bi-pencil"></i></div>
                    <div class="act-btn del" style="opacity:1;"><i class="bi bi-trash"></i></div>
                </div>
            </div>

            <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:14px;">
                <?php $__currentLoopData = $c['sub'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span style="background:var(--bg-page);border:1px solid var(--border-col);padding:3px 10px;border-radius:6px;font-size:11px;color:var(--gray);"><?php echo e($s); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;padding-top:12px;border-top:1px solid var(--border-col);">
                <span style="font-size:12.5px;color:var(--text-muted);"><?php echo e(count($c['products'] ?? [])); ?> products</span>
                <span style="font-size:11.5px;color:<?php echo e($c['color'] ?? '#000'); ?>;font-weight:700;">Active</span>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12 text-center py-5">
        <span style="font-size:12.5px;color:var(--text-muted);">No Categories</span>
    </div>
    <?php endif; ?>
</div>


<div class="modal fade" id="addCatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-tags-fill me-2" style="color:var(--primary);"></i>Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="lbl">Category Name *</label>
                        <input type="text" class="inp" placeholder="e.g. Electronics">
                    </div>
                    <div class="col-12">
                        <label class="lbl">Slug</label>
                        <input type="text" class="inp" placeholder="auto-generated from name">
                    </div>
                    <div class="col-12">
                        <label class="lbl">Parent Category</label>
                        <select class="inp">
                            <option value="">None (Top Level)</option>
                            <option>Electronics</option>
                            <option>Clothing</option>
                            <option>Home &amp; Garden</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="lbl">Description</label>
                        <textarea class="inp" rows="3" placeholder="Category description..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-o" data-bs-dismiss="modal">Cancel</button>
                <button class="btn-p"><i class="bi bi-check-lg"></i> Create Category</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/admin/categories.blade.php ENDPATH**/ ?>