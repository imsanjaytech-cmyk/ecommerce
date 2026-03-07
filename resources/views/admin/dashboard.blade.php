@extends('layouts.adminlayout')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Home / Dashboard')

@push('page-data')
<script>
    window.adminData = {
        revenue: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            data: [42000, 55000, 48000, 70000, 65000, 80000, 74000, 90000, 85000, 95000, 88000, 102000]
        },
        category: {
            labels: ['Electronics', 'Clothing', 'Home & Garden', 'Others'],
            data: [42, 28, 18, 12]
        },
        weekly: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            data: [48, 62, 55, 78, 92, 84, 41]
        },
        growth: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            data: [520, 680, 590, 820, 750, 910, 870, 1020, 980, 1150, 1080, 1280]
        },
        radar: {
            labels: ['Sales', 'Support', 'Traffic', 'Conversion', 'Retention', 'Growth'],
            current: [85, 72, 90, 68, 78, 82],
            target: [90, 80, 85, 80, 85, 90]
        },
    };
</script>
@endpush

@push('styles')
<style>
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 14px;
    }

    .bottom-row>.card-w {
        min-width: 0;
    }

    @media (min-width: 768px) {
        .stat-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 18px;
        }
    }

    .chart-row-1 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
        margin-bottom: 12px;
    }

    @media (min-width: 992px) {
        .chart-row-1 {
            grid-template-columns: 2fr 1fr;
            margin-bottom: 16px;
        }
    }

    .chart-row-2 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
        margin-bottom: 12px;
    }

    @media (min-width: 768px) {
        .chart-row-2 {
            grid-template-columns: repeat(3, 1fr);
            margin-bottom: 16px;
        }
    }

    .featured-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    @media (min-width: 768px) {
        .featured-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (min-width: 1200px) {
        .featured-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .fp-card {
        border: 1.5px solid var(--border-col);
        border-radius: 12px;
        overflow: hidden;
        transition: var(--transition);
    }

    .fp-card:hover {
        border-color: var(--pink-border);
        box-shadow: var(--shadow-md);
    }

    .fp-img {
        height: 110px;
        background: var(--pink-soft);
        overflow: hidden;
        position: relative;
    }

    @media (min-width: 576px) {
        .fp-img {
            height: 130px;
        }
    }

    .fp-body {
        padding: 10px 12px;
    }

    .fp-name {
        font-weight: 700;
        font-size: 12px;
        color: var(--dark);
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fp-cat {
        font-size: 10px;
        color: var(--text-muted);
        margin-bottom: 6px;
    }

    .fp-price {
        font-weight: 800;
        font-size: 13px;
        color: var(--primary);
    }

    .fp-sold {
        font-size: 10px;
        color: var(--text-muted);
    }

    .bottom-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }

    @media (min-width: 992px) {
        .bottom-row {
            grid-template-columns: 2fr 1fr;
        }
    }

    .orders-table-wrap {
        overflow-x: auto;
    }
</style>
@endpush

@section('content')

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-deco" style="background:var(--primary)"></div>
        <div class="stat-icon si-pink"><i class="bi bi-bag-check-fill"></i></div>
        <div class="stat-label">Total Orders</div>
        <div class="stat-value">{{ number_format($totalOrders) }}</div>
        <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> Live from database</div>
    </div>
    <div class="stat-card">
        <div class="stat-deco" style="background:#1f9c4a;"></div>
        <div class="stat-icon si-green"><i class="bi bi-box-seam-fill"></i></div>
        <div class="stat-label">Total Products</div>
        <div class="stat-value">{{ number_format($totalProducts) }}</div>
        <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> Live from database</div>
    </div>
    <div class="stat-card">
        <div class="stat-deco" style="background:#1a7cd4;"></div>
        <div class="stat-icon si-blue"><i class="bi bi-people-fill"></i></div>
        <div class="stat-label">Customers</div>
        <div class="stat-value">{{ number_format($totalCustomers) }}</div>
        <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> Registered users</div>
    </div>
    <div class="stat-card">
        <div class="stat-deco" style="background:#d97706;"></div>
        <div class="stat-icon si-orange"><i class="bi bi-currency-rupee"></i></div>
        <div class="stat-label">Revenue</div>
        <div class="stat-value">₹{{ number_format($totalRevenue / 1000, 1) }}K</div>
        <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> From delivered orders</div>
    </div>
</div>

<div class="chart-row-1">
    <div class="card-w">
        <div class="sec-header">
            <div>
                <div class="sec-title">Revenue Overview</div>
                <div class="sec-sub">Monthly revenue 2025</div>
            </div>
            <div style="display:flex;gap:6px">
                <button class="btn-o" style="padding:5px 12px;font-size:11.5px">Monthly</button>
                <button class="btn-o" style="padding:5px 12px;font-size:11.5px">Weekly</button>
            </div>
        </div>
        <div class="ch-wrap" style="height:220px"><canvas id="revenueChart"></canvas></div>
    </div>
    <div class="card-w">
        <div class="sec-header">
            <div>
                <div class="sec-title">Sales by Category</div>
                <div class="sec-sub">Top categories this month</div>
            </div>
        </div>
        <div class="ch-wrap" style="height:160px"><canvas id="categoryChart"></canvas></div>
        <div style="margin-top:12px;display:flex;flex-direction:column;gap:7px">
            @foreach([['Electronics','42%','#ff4d6d'],['Clothing','28%','#1a7cd4'],['Home & Garden','18%','#1f9c4a'],['Others','12%','#d97706']] as [$lbl,$pct,$clr])
            <div style="display:flex;align-items:center;justify-content:space-between;font-size:12px">
                <span style="display:flex;align-items:center;gap:7px"><span style="width:8px;height:8px;border-radius:50%;background:{{ $clr }};display:inline-block;flex-shrink:0"></span>{{ $lbl }}</span>
                <span style="color:var(--text-muted);font-weight:600">{{ $pct }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="chart-row-2">
    <div class="card-w">
        <div class="sec-header">
            <div>
                <div class="sec-title">Weekly Orders</div>
                <div class="sec-sub">Orders per day</div>
            </div>
        </div>
        <div class="ch-wrap" style="height:190px"><canvas id="weeklyChart"></canvas></div>
    </div>
    <div class="card-w">
        <div class="sec-header">
            <div>
                <div class="sec-title">Customer Growth</div>
                <div class="sec-sub">New registrations 2025</div>
            </div>
        </div>
        <div class="ch-wrap" style="height:190px"><canvas id="growthChart"></canvas></div>
    </div>
    <div class="card-w">
        <div class="sec-header">
            <div>
                <div class="sec-title">Performance KPIs</div>
                <div class="sec-sub">Current vs target</div>
            </div>
        </div>
        <div class="ch-wrap" style="height:190px"><canvas id="radarChart"></canvas></div>
    </div>
</div>

<div class="card-w" style="margin-bottom:12px">
    <div class="sec-header">
        <div>
            <div class="sec-title">Featured Products</div>
            <div class="sec-sub">Pinned to storefront homepage — toggle star on Products page</div>
        </div>
        <a href="{{ route('admin.products') }}" class="btn-o" style="padding:6px 14px;font-size:12px">Manage Products <i class="bi bi-arrow-right"></i></a>
    </div>
    @if($featuredProducts->count())
    <div class="featured-grid">
        @foreach($featuredProducts as $fp)
        <div class="fp-card">
            <div class="fp-img">
                <img src="{{ $fp->thumbnail_url }}" alt="{{ $fp->name }}" style="width:100%;height:100%;object-fit:cover" onerror="this.src='https://placehold.co/300x130/fff0f3/ff4d6d?text=No+Image'">
                <span style="position:absolute;top:6px;left:6px;background:linear-gradient(135deg,var(--primary),var(--secondary));color:white;font-size:9px;font-weight:700;padding:2px 7px;border-radius:5px"><i class="bi bi-star-fill"></i> Featured</span>
                <span class="bdg {{ $fp->stock_badge }}" style="position:absolute;top:6px;right:6px;font-size:9px;padding:2px 6px">{{ $fp->stock_label }}</span>
            </div>
            <div class="fp-body">
                <div class="fp-name" title="{{ $fp->name }}">{{ $fp->name }}</div>
                <div class="fp-cat">{{ $fp->category?->name ?? 'Uncategorized' }}</div>
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:2px">
                    <div>
                        <span class="fp-price">₹{{ number_format($fp->active_price) }}</span>
                        @if($fp->sale_price)<span style="font-size:10px;color:var(--text-muted);text-decoration:line-through;margin-left:3px">₹{{ number_format($fp->regular_price) }}</span>@endif
                    </div>
                    <span class="fp-sold">{{ $fp->total_sales }} sold</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div style="text-align:center;padding:36px 20px;color:var(--text-muted)">
        <i class="bi bi-star" style="font-size:36px;opacity:.2;display:block;margin-bottom:10px"></i>
        <div style="font-weight:600;font-size:14px;margin-bottom:6px">No featured products yet</div>
        <div style="font-size:13px;margin-bottom:14px">Click the star icon on any product to feature it here.</div>
        <a href="{{ route('admin.products') }}" class="btn-p" style="text-decoration:none;display:inline-flex"><i class="bi bi-box-seam me-2"></i> Go to Products</a>
    </div>
    @endif
</div>

<div class="bottom-row">

    <div class="card-w">
        <div class="sec-header">
            <div>
                <div class="sec-title">Recent Orders</div>
                <div class="sec-sub">Last 5 transactions</div>
            </div>
            <a href="{{ route('admin.orders') }}" class="btn-o" style="padding:6px 14px;font-size:12px">View All <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="orders-table-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td style="color:var(--primary);font-weight:700">
                            <a href="" style="color:inherit;text-decoration:none">#{{ $order->order_number }}</a>
                        </td>
                        <td style="font-weight:500">{{ $order->user?->name ?? 'Guest' }}</td>
                        <td>{{ $order->items_count ?? optional($order->items)->count() ?? 0 }} items</td>
                        <td style="font-weight:700">₹{{ number_format($order->total_amount) }}</td>
                        <td>
                            @php $st = $order->status; @endphp
                            @if($st === 'delivered') <span class="bdg bdg-success">Delivered</span>
                            @elseif($st === 'processing') <span class="bdg bdg-info">Processing</span>
                            @elseif($st === 'shipped') <span class="bdg bdg-warning">Shipped</span>
                            @elseif($st === 'cancelled') <span class="bdg bdg-danger">Cancelled</span>
                            @else <span class="bdg bdg-gray">{{ ucfirst($st) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:24px;color:var(--text-muted)">No orders yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-w">
        <div class="sec-header">
            <div>
                <div class="sec-title">Top Products</div>
                <div class="sec-sub">By total sales</div>
            </div>
        </div>
        @if($topProducts->count())
        @php $maxSales = $topProducts->max('total_sales') ?: 1; @endphp
        <div style="display:flex;flex-direction:column;gap:13px">
            @foreach($topProducts as $p)
            <div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px">
                    <div style="display:flex;align-items:center;gap:8px;min-width:0">
                        <span style="width:20px;height:20px;background:var(--pink-mid);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:800;color:var(--primary);flex-shrink:0">{{ $loop->iteration }}</span>
                        <span style="font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $p->name }}</span>
                    </div>
                    <span style="font-size:12px;color:var(--text-muted);font-weight:600;flex-shrink:0;margin-left:6px">{{ $p->total_sales }}</span>
                </div>
                <div style="background:#f0f2f7;border-radius:6px;height:5px">
                    <div style="height:5px;border-radius:6px;width:{{ round($p->total_sales / $maxSales * 100) }}%;background:linear-gradient(90deg,var(--primary),var(--secondary))"></div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:30px 10px;color:var(--text-muted);font-size:13px">
            <i class="bi bi-bar-chart" style="font-size:28px;opacity:.25;display:block;margin-bottom:10px"></i>
            No products yet.
        </div>
        @endif
    </div>

</div>

@endsection
