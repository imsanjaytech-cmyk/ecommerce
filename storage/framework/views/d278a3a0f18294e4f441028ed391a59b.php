
<?php $__env->startSection('page-title', 'Orders'); ?>
<?php $__env->startSection('breadcrumb', 'Home / Orders'); ?>

<?php $__env->startSection('content'); ?>


<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-deco" style="background:var(--primary);"></div>
            <div class="stat-icon si-pink"><i class="bi bi-bag-check-fill"></i></div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-value"><?php echo e(number_format(array_sum($counts))); ?></div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> All time</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-deco" style="background:#d97706;"></div>
            <div class="stat-icon si-orange"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-label">Pending</div>
            <div class="stat-value"><?php echo e(number_format($counts['pending'])); ?></div>
            <div class="stat-change ch-down"><i class="bi bi-arrow-down-right"></i> Awaiting action</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-deco" style="background:#1f9c4a;"></div>
            <div class="stat-icon si-green"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-label">Delivered</div>
            <div class="stat-value"><?php echo e(number_format($counts['delivered'])); ?></div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> Completed</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-deco" style="background:#1a7cd4;"></div>
            <div class="stat-icon si-blue"><i class="bi bi-currency-rupee"></i></div>
            <div class="stat-label">Revenue</div>
            <div class="stat-value">₹<?php echo e(number_format($totalRevenue / 1000, 1)); ?>K</div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> From delivered orders</div>
        </div>
    </div>
</div>

<div class="card-w">

    
    <div class="sec-header flex-wrap gap-2">
        <div>
            <div class="sec-title">Order Management</div>
            <div class="sec-sub"><?php echo e(number_format($orders->total())); ?> orders found</div>
        </div>
        <form method="GET" action="<?php echo e(route('admin.orders')); ?>" style="display:flex;gap:8px;">
            <input type="hidden" name="status" value="<?php echo e($status); ?>">
            <div class="search-wrap" style="width:240px;">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Order # or customer name...">
            </div>
            <button type="submit" class="btn-o"><i class="bi bi-search"></i></button>
        </form>
    </div>

    
    <div style="display:flex;gap:6px;margin-bottom:18px;border-bottom:1.5px solid var(--border-col);padding-bottom:0;flex-wrap:wrap;">
        <?php $__currentLoopData = ['all' => 'All', 'pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('admin.orders', ['status' => $s])); ?>"
            style="padding:8px 14px;font-size:13px;font-weight:600;border-radius:8px 8px 0 0;
                  text-decoration:none;border:1.5px solid transparent;margin-bottom:-1.5px;white-space:nowrap;
                  <?php echo e($status === $s
                      ? 'background:white;border-color:var(--border-col);border-bottom-color:white;color:var(--primary);'
                      : 'color:var(--text-muted);'); ?>">
            <?php echo e($label); ?>

            <?php if($s !== 'all'): ?>
            <span style="font-size:11px;opacity:.7;">(<?php echo e(number_format($counts[$s] ?? 0)); ?>)</span>
            <?php endif; ?>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="color:var(--primary);font-weight:700;font-size:13px;">
                        <a href="" style="color:inherit;text-decoration:none;">
                            #<?php echo e($order->order_number); ?>

                        </a>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px;"><?php echo e($order->user?->name ?? 'Guest'); ?></div>
                        <div style="font-size:11px;color:var(--text-muted);"><?php echo e($order->user?->email); ?></div>
                    </td>
                    <td style="font-weight:600;"> 
                        <?php echo e($order->items_count ?? optional($order->items)->count() ?? 0); ?>

                    </td>
                    <td style="font-weight:700;">₹<?php echo e(number_format($order->total_amount)); ?></td>
                    <td style="font-size:12.5px;color:var(--text-muted);">
                        <?php echo e($order->created_at->format('M d, Y')); ?>

                    </td>
                    <td>
                        <?php $st = $order->status; ?>
                        <?php if($st === 'delivered'): ?> <span class="bdg bdg-success">Delivered</span>
                        <?php elseif($st === 'processing'): ?> <span class="bdg bdg-info">Processing</span>
                        <?php elseif($st === 'shipped'): ?> <span class="bdg bdg-warning">Shipped</span>
                        <?php elseif($st === 'cancelled'): ?> <span class="bdg bdg-danger">Cancelled</span>
                        <?php else: ?> <span class="bdg bdg-gray"><?php echo e(ucfirst($st)); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="act-row">
                            <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="act-btn" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <div class="act-btn" title="Update Status"
                                onclick="updateOrderStatus(<?php echo e($order->id); ?>, '<?php echo e($order->status); ?>')">
                                <i class="bi bi-pencil"></i>
                            </div>
                            <form method="POST" action="<?php echo e(route('admin.orders.destroy', $order)); ?>"
                                onsubmit="return confirm('Delete order #<?php echo e($order->order_number); ?>?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="act-btn del" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" style="text-align:center;padding:50px;color:var(--text-muted);">
                        <i class="bi bi-bag" style="font-size:32px;opacity:.25;display:block;margin-bottom:10px;"></i>
                        No orders found
                        <?php if($search): ?> for "<strong><?php echo e($search); ?></strong>" <?php endif; ?>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($orders->hasPages()): ?>
    <div class="pgn">
        <span class="pgn-info">
            Showing <?php echo e($orders->firstItem()); ?>–<?php echo e($orders->lastItem()); ?>

            of <?php echo e(number_format($orders->total())); ?> orders
        </span>
        <div class="pgn-btns">
            <?php if($orders->onFirstPage()): ?>
            <div class="pgn-btn" style="opacity:.4;pointer-events:none;"><i class="bi bi-chevron-left" style="font-size:10px;"></i></div>
            <?php else: ?>
            <a href="<?php echo e($orders->previousPageUrl()); ?>" class="pgn-btn" style="text-decoration:none;"><i class="bi bi-chevron-left" style="font-size:10px;"></i></a>
            <?php endif; ?>

            <?php $__currentLoopData = $orders->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($url); ?>" class="pgn-btn <?php echo e($page === $orders->currentPage() ? 'active' : ''); ?>" style="text-decoration:none;"><?php echo e($page); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if($orders->hasMorePages()): ?>
            <a href="<?php echo e($orders->nextPageUrl()); ?>" class="pgn-btn" style="text-decoration:none;"><i class="bi bi-chevron-right" style="font-size:10px;"></i></a>
            <?php else: ?>
            <div class="pgn-btn" style="opacity:.4;pointer-events:none;"><i class="bi bi-chevron-right" style="font-size:10px;"></i></div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

</div>


<div id="statusModal" style="display:none;position:fixed;inset:0;z-index:1060;background:rgba(0,0,0,.45);align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;width:100%;max-width:380px;padding:28px;margin:16px;">
        <div style="font-size:15px;font-weight:700;margin-bottom:18px;">Update Order Status</div>
        <select id="statusSelect" class="form-select mb-4" style="font-family:'Poppins',sans-serif;font-size:14px;">
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="shipped">Shipped</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <div style="display:flex;gap:10px;justify-content:flex-end;">
            <button class="btn-o" onclick="closeStatusModal()">Cancel</button>
            <button class="btn-p" onclick="submitStatusUpdate()">Save</button>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    let _statusOrderId = null;

    function updateOrderStatus(orderId, currentStatus) {
        _statusOrderId = orderId;
        document.getElementById('statusSelect').value = currentStatus;
        const modal = document.getElementById('statusModal');
        modal.style.display = 'flex';
    }

    function closeStatusModal() {
        document.getElementById('statusModal').style.display = 'none';
        _statusOrderId = null;
    }

    async function submitStatusUpdate() {
        if (!_statusOrderId) return;

        const status = document.getElementById('statusSelect').value;

        const res = await fetch(`/admin/orders/${_statusOrderId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                status
            }),
        });

        const data = await res.json();
        if (data.success) {
            showToast(data.message);
            closeStatusModal();
            setTimeout(() => location.reload(), 800);
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/admin/orders.blade.php ENDPATH**/ ?>