@extends('layouts.adminlayout')
@section('page-title', 'Orders')
@section('breadcrumb', 'Home / Orders')

@section('content')

{{-- ── Stat Cards ── --}}
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-deco" style="background:var(--primary);"></div>
            <div class="stat-icon si-pink"><i class="bi bi-bag-check-fill"></i></div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">{{ number_format(array_sum($counts)) }}</div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> All time</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-deco" style="background:#d97706;"></div>
            <div class="stat-icon si-orange"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ number_format($counts['pending']) }}</div>
            <div class="stat-change ch-down"><i class="bi bi-arrow-down-right"></i> Awaiting action</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-deco" style="background:#1f9c4a;"></div>
            <div class="stat-icon si-green"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-label">Delivered</div>
            <div class="stat-value">{{ number_format($counts['delivered']) }}</div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> Completed</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-deco" style="background:#1a7cd4;"></div>
            <div class="stat-icon si-blue"><i class="bi bi-currency-rupee"></i></div>
            <div class="stat-label">Revenue</div>
            <div class="stat-value">₹{{ number_format($totalRevenue / 1000, 1) }}K</div>
            <div class="stat-change ch-up"><i class="bi bi-arrow-up-right"></i> From delivered orders</div>
        </div>
    </div>
</div>

<div class="card-w">

    {{-- ── Header ── --}}
    <div class="sec-header flex-wrap gap-2">
        <div>
            <div class="sec-title">Order Management</div>
            <div class="sec-sub">{{ number_format($orders->total()) }} orders found</div>
        </div>
        <form method="GET" action="{{ route('admin.orders') }}" style="display:flex;gap:8px;">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="search-wrap" style="width:240px;">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Order # or customer name...">
            </div>
            <button type="submit" class="btn-o"><i class="bi bi-search"></i></button>
        </form>
    </div>

    {{-- ── Status Tabs ── --}}
    <div style="display:flex;gap:6px;margin-bottom:18px;border-bottom:1.5px solid var(--border-col);padding-bottom:0;flex-wrap:wrap;">
        @foreach(['all' => 'All', 'pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $s => $label)
        <a href="{{ route('admin.orders', ['status' => $s]) }}"
            style="padding:8px 14px;font-size:13px;font-weight:600;border-radius:8px 8px 0 0;
                  text-decoration:none;border:1.5px solid transparent;margin-bottom:-1.5px;white-space:nowrap;
                  {{ $status === $s
                      ? 'background:white;border-color:var(--border-col);border-bottom-color:white;color:var(--primary);'
                      : 'color:var(--text-muted);' }}">
            {{ $label }}
            @if($s !== 'all')
            <span style="font-size:11px;opacity:.7;">({{ number_format($counts[$s] ?? 0) }})</span>
            @endif
        </a>
        @endforeach
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
                @forelse($orders as $order)
                <tr>
                    <td style="color:var(--primary);font-weight:700;font-size:13px;">
                        <a href="" style="color:inherit;text-decoration:none;">
                            #{{ $order->order_number }}
                        </a>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $order->user?->name ?? 'Guest' }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $order->user?->email }}</div>
                    </td>
                    <td style="font-weight:600;"> 
                        {{ $order->items_count ?? optional($order->items)->count() ?? 0 }}
                    </td>
                    <td style="font-weight:700;">₹{{ number_format($order->total_amount) }}</td>
                    <td style="font-size:12.5px;color:var(--text-muted);">
                        {{ $order->created_at->format('M d, Y') }}
                    </td>
                    <td>
                        @php $st = $order->status; @endphp
                        @if($st === 'delivered') <span class="bdg bdg-success">Delivered</span>
                        @elseif($st === 'processing') <span class="bdg bdg-info">Processing</span>
                        @elseif($st === 'shipped') <span class="bdg bdg-warning">Shipped</span>
                        @elseif($st === 'cancelled') <span class="bdg bdg-danger">Cancelled</span>
                        @else <span class="bdg bdg-gray">{{ ucfirst($st) }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="act-row">
                            <a href="{{ route('admin.orders.show', $order) }}" class="act-btn" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <div class="act-btn" title="Update Status"
                                onclick="updateOrderStatus({{ $order->id }}, '{{ $order->status }}')">
                                <i class="bi bi-pencil"></i>
                            </div>
                            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}"
                                onsubmit="return confirm('Delete order #{{ $order->order_number }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn del" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:50px;color:var(--text-muted);">
                        <i class="bi bi-bag" style="font-size:32px;opacity:.25;display:block;margin-bottom:10px;"></i>
                        No orders found
                        @if($search) for "<strong>{{ $search }}</strong>" @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="pgn">
        <span class="pgn-info">
            Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }}
            of {{ number_format($orders->total()) }} orders
        </span>
        <div class="pgn-btns">
            @if($orders->onFirstPage())
            <div class="pgn-btn" style="opacity:.4;pointer-events:none;"><i class="bi bi-chevron-left" style="font-size:10px;"></i></div>
            @else
            <a href="{{ $orders->previousPageUrl() }}" class="pgn-btn" style="text-decoration:none;"><i class="bi bi-chevron-left" style="font-size:10px;"></i></a>
            @endif

            @foreach($orders->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $page => $url)
            <a href="{{ $url }}" class="pgn-btn {{ $page === $orders->currentPage() ? 'active' : '' }}" style="text-decoration:none;">{{ $page }}</a>
            @endforeach

            @if($orders->hasMorePages())
            <a href="{{ $orders->nextPageUrl() }}" class="pgn-btn" style="text-decoration:none;"><i class="bi bi-chevron-right" style="font-size:10px;"></i></a>
            @else
            <div class="pgn-btn" style="opacity:.4;pointer-events:none;"><i class="bi bi-chevron-right" style="font-size:10px;"></i></div>
            @endif
        </div>
    </div>
    @endif

</div>

{{-- ── Update Status Modal ── --}}
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

@endsection

@push('scripts')
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
@endpush