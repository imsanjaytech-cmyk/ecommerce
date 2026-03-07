
<?php $__env->startSection('page-title', 'Dashboard'); ?>
<?php $__env->startSection('breadcrumb', 'Home / Dashboard'); ?>

<?php $__env->startPush('page-data'); ?>
<script>
    window.adminData = {
        revenue:  { labels:['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'], data:[42000,55000,48000,70000,65000,80000,74000,90000,85000,95000,88000,102000] },
        category: { labels:['Electronics','Clothing','Home & Garden','Others'], data:[42,28,18,12] },
        weekly:   { labels:['Mon','Tue','Wed','Thu','Fri','Sat','Sun'], data:[48,62,55,78,92,84,41] },
        growth:   { labels:['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'], data:[520,680,590,820,750,910,870,1020,980,1150,1080,1280] },
        radar:    { labels:['Sales','Support','Traffic','Conversion','Retention','Growth'], current:[85,72,90,68,78,82], target:[90,80,85,80,85,90] },
    };
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-deco" style="background:var(--primary);"></div>
            <div class="stat-icon si-pink"><i class="bi bi-bag-check-fill"></i></div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-value"><?php echo e(number_format($totalOrders)); ?></div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> Live from database</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-deco" style="background:#1f9c4a;"></div>
            <div class="stat-icon si-green"><i class="bi bi-box-seam-fill"></i></div>
            <div class="stat-label">Total Products</div>
            <div class="stat-value"><?php echo e(number_format($totalProducts)); ?></div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> Live from database</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-deco" style="background:#1a7cd4;"></div>
            <div class="stat-icon si-blue"><i class="bi bi-people-fill"></i></div>
            <div class="stat-label">Customers</div>
            <div class="stat-value"><?php echo e(number_format($totalCustomers)); ?></div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> Registered users</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-deco" style="background:#d97706;"></div>
            <div class="stat-icon si-orange"><i class="bi bi-currency-rupee"></i></div>
            <div class="stat-label">Revenue</div>
            <div class="stat-value">₹<?php echo e(number_format($totalRevenue / 1000, 1)); ?>K</div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> From delivered orders</div>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card-w">
            <div class="sec-header">
                <div><div class="sec-title">Revenue Overview</div><div class="sec-sub">Monthly revenue 2025</div></div>
                <div style="display:flex;gap:6px;">
                    <button class="btn-o" style="padding:5px 12px;font-size:11.5px;">Monthly</button>
                    <button class="btn-o" style="padding:5px 12px;font-size:11.5px;">Weekly</button>
                </div>
            </div>
            <div class="ch-wrap" style="height:240px;"><canvas id="revenueChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-w">
            <div class="sec-header">
                <div><div class="sec-title">Sales by Category</div><div class="sec-sub">Top categories this month</div></div>
            </div>
            <div class="ch-wrap" style="height:180px;"><canvas id="categoryChart"></canvas></div>
            <div style="margin-top:14px;display:flex;flex-direction:column;gap:8px;">
                <?php $__currentLoopData = [['Electronics','42%','#ff4d6d'],['Clothing','28%','#1a7cd4'],['Home & Garden','18%','#1f9c4a'],['Others','12%','#d97706']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$lbl,$pct,$clr]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="display:flex;align-items:center;justify-content:space-between;font-size:12px;">
                    <span style="display:flex;align-items:center;gap:7px;">
                        <span style="width:8px;height:8px;border-radius:50%;background:<?php echo e($clr); ?>;display:inline-block;"></span>
                        <?php echo e($lbl); ?>

                    </span>
                    <span style="color:var(--text-muted);font-weight:600;"><?php echo e($pct); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-lg-4">
        <div class="card-w">
            <div class="sec-header"><div><div class="sec-title">Weekly Orders</div><div class="sec-sub">Orders per day</div></div></div>
            <div class="ch-wrap" style="height:200px;"><canvas id="weeklyChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-w">
            <div class="sec-header"><div><div class="sec-title">Customer Growth</div><div class="sec-sub">New registrations 2025</div></div></div>
            <div class="ch-wrap" style="height:200px;"><canvas id="growthChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-w">
            <div class="sec-header"><div><div class="sec-title">Performance KPIs</div><div class="sec-sub">Current vs target</div></div></div>
            <div class="ch-wrap" style="height:200px;"><canvas id="radarChart"></canvas></div>
        </div>
    </div>
</div>


<div class="card-w mb-4">
    <div class="sec-header">
        <div>
            <div class="sec-title">Featured Products</div>
            <div class="sec-sub">Pinned to storefront homepage — toggle star on Products page</div>
        </div>
        <a href="<?php echo e(route('admin.products')); ?>" class="btn-o" style="padding:6px 14px;font-size:12px;">
            Manage Products <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <?php if($featuredProducts->count()): ?>
    <div class="row g-3">
        <?php $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-4 col-lg-3 col-sm-6">
            <div style="border:1.5px solid var(--border-col);border-radius:13px;overflow:hidden;transition:var(--transition);"
                 onmouseover="this.style.cssText='border:1.5px solid var(--pink-border);border-radius:13px;overflow:hidden;transition:var(--transition);box-shadow:var(--shadow-md);'"
                 onmouseout="this.style.cssText='border:1.5px solid var(--border-col);border-radius:13px;overflow:hidden;transition:var(--transition);'">
                <div style="height:140px;background:var(--pink-soft);overflow:hidden;position:relative;">
                    <img src="<?php echo e($fp->thumbnail_url); ?>"
                         alt="<?php echo e($fp->name); ?>"
                         style="width:100%;height:100%;object-fit:cover;"
                         onerror="this.src='https://placehold.co/300x140/fff0f3/ff4d6d?text=No+Image'">
                    <span style="position:absolute;top:8px;left:8px;background:linear-gradient(135deg,var(--primary),var(--secondary));color:white;font-size:10px;font-weight:700;padding:2px 8px;border-radius:6px;">
                        <i class="bi bi-star-fill me-1"></i>Featured
                    </span>
                    <span class="bdg <?php echo e($fp->stock_badge); ?>" style="position:absolute;top:8px;right:8px;font-size:10px;">
                        <?php echo e($fp->stock_label); ?>

                    </span>
                </div>
                <div style="padding:12px 14px;">
                    <div style="font-weight:700;font-size:13px;color:var(--dark);margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="<?php echo e($fp->name); ?>">
                        <?php echo e($fp->name); ?>

                    </div>
                    <div style="font-size:11px;color:var(--text-muted);margin-bottom:8px;">
                        <?php echo e($fp->category?->name ?? 'Uncategorized'); ?>

                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <span style="font-weight:800;font-size:15px;color:var(--primary);">₹<?php echo e(number_format($fp->active_price)); ?></span>
                            <?php if($fp->sale_price): ?>
                                <span style="font-size:11px;color:var(--text-muted);text-decoration:line-through;margin-left:4px;">₹<?php echo e(number_format($fp->regular_price)); ?></span>
                            <?php endif; ?>
                        </div>
                        <span style="font-size:11px;color:var(--text-muted);"><?php echo e($fp->total_sales); ?> sold</span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php else: ?>
    <div style="text-align:center;padding:40px 20px;color:var(--text-muted);">
        <i class="bi bi-star" style="font-size:38px;opacity:.2;display:block;margin-bottom:12px;"></i>
        <div style="font-weight:600;font-size:14px;margin-bottom:6px;">No featured products yet</div>
        <div style="font-size:13px;margin-bottom:16px;">Click the star icon on any product to feature it here.</div>
        <a href="<?php echo e(route('admin.products')); ?>" class="btn-p" style="text-decoration:none;display:inline-flex;">
            <i class="bi bi-box-seam me-2"></i> Go to Products
        </a>
    </div>
    <?php endif; ?>
</div>


<div class="row g-3">

    <div class="col-lg-8">
        <div class="card-w">
            <div class="sec-header">
                <div><div class="sec-title">Recent Orders</div><div class="sec-sub">Last 5 transactions</div></div>
                <a href="<?php echo e(route('admin.orders')); ?>" class="btn-o" style="padding:6px 14px;font-size:12px;">
                    View All <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Order ID</th><th>Customer</th><th>Items</th><th>Total</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="color:var(--primary);font-weight:700;font-size:13px;">
                            <a href="#" style="color:inherit;text-decoration:none;">
                                #<?php echo e($order->order_number); ?>

                            </a>
                        </td>
                        <td style="font-weight:500;"><?php echo e($order->user?->name ?? 'Guest'); ?></td>
                        <td><?php echo e($order->items_count ?? optional($order->items)->count() ?? 0); ?> items</td>
                        <td style="font-weight:700;">₹<?php echo e(number_format($order->total_amount)); ?></td>
                        <td>
                            <?php $st = $order->status; ?>
                            <?php if($st === 'delivered'): ?>      <span class="bdg bdg-success">Delivered</span>
                            <?php elseif($st === 'processing'): ?> <span class="bdg bdg-info">Processing</span>
                            <?php elseif($st === 'shipped'): ?>    <span class="bdg bdg-warning">Shipped</span>
                            <?php elseif($st === 'cancelled'): ?>  <span class="bdg bdg-danger">Cancelled</span>
                            <?php else: ?>                         <span class="bdg bdg-gray"><?php echo e(ucfirst($st)); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;padding:30px;color:var(--text-muted);">
                            No orders yet.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-w">
            <div class="sec-header">
                <div><div class="sec-title">Top Products</div><div class="sec-sub">By total sales</div></div>
            </div>
            <?php if($topProducts->count()): ?>
            <?php $maxSales = $topProducts->max('total_sales') ?: 1; ?>
            <div style="display:flex;flex-direction:column;gap:14px;">
                <?php $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;">
                        <div style="display:flex;align-items:center;gap:8px;min-width:0;">
                            <span style="width:20px;height:20px;background:var(--pink-mid);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:800;color:var(--primary);flex-shrink:0;">
                                <?php echo e($loop->iteration); ?>

                            </span>
                            <span style="font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                <?php echo e($p->name); ?>

                            </span>
                        </div>
                        <span style="font-size:12px;color:var(--text-muted);font-weight:600;flex-shrink:0;margin-left:6px;">
                            <?php echo e($p->total_sales); ?>

                        </span>
                    </div>
                    <div style="background:#f0f2f7;border-radius:6px;height:5px;">
                        <div style="height:5px;border-radius:6px;width:<?php echo e(round($p->total_sales / $maxSales * 100)); ?>%;background:linear-gradient(90deg,var(--primary),var(--secondary));"></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <div style="text-align:center;padding:30px 10px;color:var(--text-muted);font-size:13px;">
                <i class="bi bi-bar-chart" style="font-size:28px;opacity:.25;display:block;margin-bottom:10px;"></i>
                No products yet.
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\SanjayS\ecommerce\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>