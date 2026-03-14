@extends('layouts.adminlayout')
@section('page-title', 'Orders')
@section('breadcrumb', 'Orders')

@section('content')

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-deco" style="background:var(--primary);"></div>
            <div class="stat-icon si-pink"><i class="bi bi-bag-check-fill"></i></div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-value" id="stat-total">{{ number_format(array_sum($counts)) }}</div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> All time</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-deco" style="background:#d97706;"></div>
            <div class="stat-icon si-orange"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-label">Pending</div>
            <div class="stat-value" id="stat-pending">{{ number_format($counts['pending']) }}</div>
            <div class="stat-change ch-down"><i class="bi bi-arrow-down-right"></i> Awaiting action</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-deco" style="background:#1f9c4a;"></div>
            <div class="stat-icon si-green"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-label">Delivered</div>
            <div class="stat-value" id="stat-delivered">{{ number_format($counts['delivered']) }}</div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> Completed</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-deco" style="background:#1a7cd4;"></div>
            <div class="stat-icon si-blue"><i class="bi bi-currency-rupee"></i></div>
            <div class="stat-label">Revenue</div>
            <div class="stat-value" id="stat-revenue">₹{{ number_format($totalRevenue / 1000, 1) }}K</div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> From delivered orders</div>
        </div>
    </div>
</div>
<div class="card-w">

    {{-- ── Header ── --}}
    <div class="sec-header flex-wrap gap-2">
        <div>
            <div class="sec-title">Order Management</div>
            <div class="sec-sub" id="ordersSubtitle">{{ number_format($orders->total()) }} orders found</div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            <div class="search-wrap" style="width:240px;">
                <i class="bi bi-search"></i>
                <input type="text" id="orderSearch" value="{{ $search }}" placeholder="Order # or customer name...">
            </div>
            <select class="inp" id="perPageSelect" style="width:85px;padding:8px 12px;">
                <option value="15">15 / pg</option>
                <option value="25">25 / pg</option>
                <option value="50">50 / pg</option>
            </select>
        </div>
    </div>

    {{-- ── Status Tabs ── --}}
    <div style="display:flex;gap:6px;margin-bottom:18px;border-bottom:1.5px solid var(--border-col);padding-bottom:0;flex-wrap:wrap;" id="statusTabs">
        @foreach(['all' => 'All', 'pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $s => $label)
        <a href="#" class="order-tab {{ $status === $s ? 'active-tab' : '' }}" data-status="{{ $s }}"
            style="padding:8px 14px;font-size:13px;font-weight:600;border-radius:8px 8px 0 0;
                   text-decoration:none;border:1.5px solid transparent;margin-bottom:-1.5px;white-space:nowrap;
                   {{ $status === $s
                       ? 'background:white;border-color:var(--border-col);border-bottom-color:white;color:var(--primary);'
                       : 'color:var(--text-muted);' }}">
            {{ $label }}
            @if($s !== 'all')
            <span class="tab-count" data-status="{{ $s }}" style="font-size:11px;opacity:.7;">({{ number_format($counts[$s] ?? 0) }})</span>
            @endif
        </a>
        @endforeach
    </div>

    {{-- ── Table ── --}}
    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th class="col-items">Items</th>
                    <th>Total</th>
                    <th class="col-date">Date</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="ordersBody">
                <tr>
                    <td colspan="7" style="text-align:center;padding:50px;color:var(--text-muted);">
                        <div class="spinner-border spinner-border-sm me-2" style="color:var(--primary);"></div>
                        Loading orders...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="pgn" id="paginationBar" style="display:none;">
        <span class="pgn-info" id="paginationInfo"></span>
        <div class="pgn-btns" id="paginationBtns"></div>
    </div>

</div>

{{-- ── Update Status Modal ── --}}
<div class="modal fade" id="statusModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width:380px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-size:15px;font-weight:700;">
                    <i class="bi bi-pencil-square me-2" style="color:var(--primary);"></i>Update Order Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:22px;">
                <p style="font-size:12.5px;color:var(--text-muted);margin-bottom:14px;">
                    Order <strong id="statusOrderNum" style="color:var(--dark);"></strong>
                </p>
                <label class="lbl">New Status</label>
                <select id="statusSelect" class="inp">
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="modal-footer" style="gap:8px;">
                <button class="btn-o" data-bs-dismiss="modal">Cancel</button>
                <button class="btn-p" id="saveStatusBtn">
                    <span id="saveStatusText"><i class="bi bi-check-lg"></i> Save</span>
                    <span id="saveStatusSpinner" class="spinner-border spinner-border-sm" style="display:none;"></span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ── Delete Confirm Modal ── --}}
<div class="modal fade" id="deleteOrderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content">
            <div class="modal-body" style="padding:32px;text-align:center;">
                <div style="width:64px;height:64px;background:#fee8eb;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
                    <i class="bi bi-trash-fill" style="font-size:26px;color:var(--danger);"></i>
                </div>
                <h5 style="font-weight:700;color:var(--dark);margin-bottom:8px;">Delete Order?</h5>
                <p style="color:var(--text-muted);font-size:13.5px;margin-bottom:24px;">
                    Delete order <strong id="deleteOrderNum" style="color:var(--dark);"></strong>? This cannot be undone.
                </p>
                <div style="display:flex;gap:10px;justify-content:center;">
                    <button class="btn-o" data-bs-dismiss="modal" style="min-width:110px;">Cancel</button>
                    <button class="btn-p" id="confirmDeleteOrderBtn"
                        style="background:linear-gradient(135deg,#dc3545,#c62535);box-shadow:0 4px 14px rgba(220,53,69,0.3);min-width:110px;">
                        <i class="bi bi-trash"></i> Delete
                        <span id="deleteOrderSpinner" class="spinner-border spinner-border-sm" style="display:none;"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 575px) {
    .col-items, .col-date { display: none; }
}
.order-tab { transition: color 0.15s; }
.order-tab:hover { color: var(--primary) !important; }
</style>

@endsection

@push('scripts')
<script>
window.ORDERS = {
    routes: {
        list:    '{{ route("admin.orders") }}',
        show:    id => `/admin/orders/${id}`,
        status:  id => `/admin/orders/${id}/status`,
        destroy: id => `/admin/orders/${id}`,
    },
    csrf:          '{{ csrf_token() }}',
    initialStatus: '{{ $status }}',
    initialSearch: '{{ $search }}',
};
</script>
<script src="{{ asset('js/orders.js') }}"></script>
@endpush
