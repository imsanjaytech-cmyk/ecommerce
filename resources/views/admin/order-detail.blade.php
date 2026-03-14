@extends('layouts.adminlayout')
@section('page-title', 'Order #' . $order->order_number)
@section('breadcrumb', 'Orders / #' . $order->order_number)

@section('content')

{{-- ── Back + Actions ── --}}
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <a href="{{ route('admin.orders') }}" class="btn-o">
        <i class="bi bi-arrow-left"></i> Back to Orders
    </a>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <button class="btn-o" onclick="window.print()">
            <i class="bi bi-printer"></i> Print
        </button>
        <button class="btn-p" id="updateStatusBtn"
            data-id="{{ $order->id }}"
            data-num="{{ $order->order_number }}"
            data-status="{{ $order->status }}">
            <i class="bi bi-pencil"></i> Update Status
        </button>
    </div>
</div>

<div class="row g-4">

    {{-- ── LEFT COLUMN ── --}}
    <div class="col-lg-8">

        {{-- Order Items --}}
        <div class="card-w mb-4">
            <div class="sec-header mb-3">
                <div>
                    <div class="sec-title">Order Items</div>
                    <div class="sec-sub">{{ $order->items->count() }} item(s)</div>
                </div>
            </div>

            @forelse($order->items as $item)
            <div style="display:flex;align-items:center;gap:14px;padding:14px 0;border-bottom:1px solid var(--border-col);">
                {{-- Image --}}
                <div style="width:60px;height:60px;border-radius:10px;overflow:hidden;border:1.5px solid var(--border-col);flex-shrink:0;background:var(--pink-soft);">
                    @if($item->product_image)
                        <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}"
                             style="width:100%;height:100%;object-fit:cover;"
                             onerror="this.parentElement.innerHTML='<div style=\'width:100%;height:100%;display:flex;align-items:center;justify-content:center;\'><i class=\'bi bi-box-seam\' style=\'color:var(--secondary);font-size:18px;\'></i></div>'">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-box-seam" style="color:var(--secondary);font-size:18px;"></i>
                        </div>
                    @endif
                </div>

                {{-- Details --}}
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:14px;color:var(--dark);">{{ $item->product_name }}</div>
                    @if($item->product_sku)
                    <div style="font-size:11px;color:var(--text-muted);margin-top:2px;font-family:monospace;">SKU: {{ $item->product_sku }}</div>
                    @endif
                    @if($item->product)
                    <a href="{{ route('admin.products') }}" style="font-size:11px;color:var(--primary);margin-top:2px;display:inline-block;">
                        View Product <i class="bi bi-arrow-up-right" style="font-size:9px;"></i>
                    </a>
                    @else
                    <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">(Product deleted)</div>
                    @endif
                </div>

                {{-- Qty × Price --}}
                <div style="text-align:right;flex-shrink:0;">
                    <div style="font-size:13px;color:var(--text-muted);">
                        {{ $item->quantity }} × ₹{{ number_format($item->unit_price) }}
                    </div>
                    <div style="font-weight:700;font-size:15px;color:var(--dark);margin-top:2px;">
                        ₹{{ number_format($item->subtotal) }}
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:40px;color:var(--text-muted);">
                <i class="bi bi-box-seam" style="font-size:32px;opacity:.2;display:block;margin-bottom:8px;"></i>
                No items found for this order.
            </div>
            @endforelse

            {{-- Totals --}}
            @php
                $deliveryFee  = $order->total_amount >= 1500 ? 0 : 99;
                $itemsSubtotal = $order->items->sum('subtotal');
            @endphp
            <div style="margin-top:16px;padding-top:16px;border-top:1.5px solid var(--border-col);">
                <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:13.5px;color:var(--text-muted);">
                    <span>Items Subtotal</span>
                    <span>₹{{ number_format($itemsSubtotal) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:13.5px;color:var(--text-muted);">
                    <span>Delivery Fee</span>
                    <span>{{ $deliveryFee === 0 ? 'FREE' : '₹' . $deliveryFee }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:10px 0 0;font-size:16px;font-weight:800;color:var(--dark);border-top:1px solid var(--border-col);margin-top:6px;">
                    <span>Order Total</span>
                    <span style="color:var(--primary);">₹{{ number_format($order->total_amount) }}</span>
                </div>
            </div>
        </div>

        {{-- Shipping Address --}}
        <div class="card-w mb-4">
            <div class="sec-title mb-3"><i class="bi bi-geo-alt-fill me-2" style="color:var(--primary);"></i>Shipping Address</div>
            <div style="font-size:14px;color:var(--dark);line-height:1.7;white-space:pre-line;">
                @if(is_array($order->shipping_address))
                    @foreach($order->shipping_address as $key => $val)
                        @if($val)<span style="color:var(--text-muted);font-size:11.5px;text-transform:uppercase;letter-spacing:.5px;">{{ $key }}:</span> {{ $val }}<br>@endif
                    @endforeach
                @else
                    {{ $order->shipping_address ?? 'No address provided.' }}
                @endif
            </div>
        </div>

    </div>

    {{-- ── RIGHT COLUMN ── --}}
    <div class="col-lg-4">

        {{-- Order Info --}}
        <div class="card-w mb-4">
            <div class="sec-title mb-3">Order Info</div>

            <div style="display:flex;flex-direction:column;gap:12px;">

                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:12px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Order #</span>
                    <span style="font-weight:700;color:var(--primary);">#{{ $order->order_number }}</span>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:12px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Date</span>
                    <span style="font-weight:600;font-size:13px;">{{ $order->created_at->format('M d, Y — h:i A') }}</span>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:12px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Status</span>
                    @php $st = $order->status; @endphp
                    @if($st === 'delivered')  <span class="bdg bdg-success">Delivered</span>
                    @elseif($st === 'processing') <span class="bdg bdg-info">Processing</span>
                    @elseif($st === 'shipped')    <span class="bdg bdg-warning">Shipped</span>
                    @elseif($st === 'cancelled')  <span class="bdg bdg-danger">Cancelled</span>
                    @else <span class="bdg bdg-gray">{{ ucfirst($st) }}</span>
                    @endif
                </div>

                <div style="border-top:1px solid var(--border-col);padding-top:12px;display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:12px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Payment</span>
                    <span class="bdg {{ $order->payment_status === 'paid' ? 'bdg-success' : 'bdg-warning' }}">
                        {{ ucfirst($order->payment_status ?? 'pending') }}
                    </span>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:12px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Method</span>
                    <span style="font-weight:600;font-size:13px;">{{ ucfirst($order->payment_method ?? '—') }}</span>
                </div>

                @if($order->razorpay_order_id)
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                    <span style="font-size:12px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;flex-shrink:0;">Razorpay ID</span>
                    <span style="font-size:11.5px;font-family:monospace;color:var(--dark);word-break:break-all;text-align:right;">{{ $order->razorpay_order_id }}</span>
                </div>
                @endif

                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:12px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Paid</span>
                    <span style="font-weight:700;font-size:14px;color:#1f9c4a;">₹{{ number_format($order->paid_amount ?? 0) }}</span>
                </div>

            </div>
        </div>

        {{-- Customer Info --}}
        <div class="card-w mb-4">
            <div class="sec-title mb-3"><i class="bi bi-person-fill me-2" style="color:var(--primary);"></i>Customer</div>

            @if($order->user)
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                <div style="width:42px;height:42px;border-radius:11px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:16px;flex-shrink:0;">
                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight:700;font-size:14px;color:var(--dark);">{{ $order->user->name }}</div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $order->user->email }}</div>
                </div>
            </div>
            <a href="{{ route('admin.customers') }}" class="btn-o" style="width:100%;justify-content:center;font-size:12px;">
                <i class="bi bi-person"></i> View Customer Profile
            </a>
            @else
            <div style="display:flex;align-items:center;gap:10px;color:var(--text-muted);">
                <i class="bi bi-person-slash" style="font-size:20px;"></i>
                <span style="font-size:13px;">Guest Order (user deleted)</span>
            </div>
            @endif
        </div>

        {{-- Quick Status Update --}}
        <div class="card-w" style="border:1.5px solid var(--pink-border);background:var(--pink-soft);">
            <div class="sec-title mb-3"><i class="bi bi-arrow-repeat me-2" style="color:var(--primary);"></i>Quick Status Update</div>
            <select class="inp mb-3" id="quickStatusSelect">
                @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button class="btn-p" id="quickUpdateBtn" data-id="{{ $order->id }}" data-num="{{ $order->order_number }}" style="width:100%;justify-content:center;">
                <span id="quickUpdateText"><i class="bi bi-check-lg"></i> Update Status</span>
                <span id="quickUpdateSpinner" class="spinner-border spinner-border-sm" style="display:none;"></span>
            </button>
        </div>

    </div>
</div>

{{-- ── Status Update Modal ── --}}
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
                    Order <strong id="modalOrderNum" style="color:var(--dark);"></strong>
                </p>
                <label class="lbl">New Status</label>
                <select id="modalStatusSelect" class="inp">
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="modal-footer" style="gap:8px;">
                <button class="btn-o" data-bs-dismiss="modal">Cancel</button>
                <button class="btn-p" id="saveModalStatusBtn">
                    <span id="saveModalText"><i class="bi bi-check-lg"></i> Save</span>
                    <span id="saveModalSpinner" class="spinner-border spinner-border-sm" style="display:none;"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .sidebar, .topbar, .btn-p, .btn-o, #updateStatusBtn, .card-w:last-child { display: none !important; }
    .main-wrap { margin-left: 0 !important; }
    .col-lg-4 { display: none; }
    .col-lg-8 { width: 100% !important; flex: 0 0 100% !important; max-width: 100% !important; }
}
</style>

@endsection

@push('scripts')
<script>
(function () {
    const CSRF    = '{{ csrf_token() }}';
    const orderId = {{ $order->id }};
    const statusRoute = id => `/admin/orders/${id}/status`;

    function toast(message, type = 'success') {
        let container = document.getElementById('toastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.style.cssText = 'position:fixed;bottom:20px;right:20px;display:flex;flex-direction:column;gap:8px;z-index:9999;pointer-events:none;';
            document.body.appendChild(container);
        }
        const colors = {
            success: { bg:'#e8f8ee', border:'#1f9c4a', text:'#1f9c4a', icon:'bi-check-circle-fill' },
            error:   { bg:'#fee8eb', border:'#dc3545', text:'#dc3545', icon:'bi-x-circle-fill' },
        };
        const c = colors[type] || colors.success;
        const t = document.createElement('div');
        t.style.cssText = `background:${c.bg};border:1.5px solid ${c.border};color:${c.text};
            padding:11px 16px;border-radius:11px;font-size:13px;font-weight:600;
            display:flex;align-items:center;gap:9px;box-shadow:0 4px 20px rgba(0,0,0,0.1);
            pointer-events:all;min-width:240px;`;
        t.innerHTML = `<i class="bi ${c.icon}" style="font-size:16px;flex-shrink:0;"></i><span>${message}</span>`;
        container.appendChild(t);
        setTimeout(() => { t.style.opacity='0'; t.style.transition='opacity .3s'; setTimeout(()=>t.remove(),300); }, 3200);
    }

    function updateBadge(status) {
        const map = {
            delivered:  ['bdg-success', 'Delivered'],
            processing: ['bdg-info',    'Processing'],
            shipped:    ['bdg-warning', 'Shipped'],
            cancelled:  ['bdg-danger',  'Cancelled'],
            pending:    ['bdg-gray',    'Pending'],
        };
        const [cls, label] = map[status] || ['bdg-gray', status];
        document.querySelectorAll('.order-status-badge').forEach(el => {
            el.className = `bdg ${cls} order-status-badge`;
            el.textContent = label;
        });
    }

    async function doStatusUpdate(id, status, btnText, btnSpinner, btn) {
        btn.disabled            = true;
        btnText.style.display   = 'none';
        btnSpinner.style.display= 'inline-block';

        try {
            const res  = await fetch(statusRoute(id), {
                method: 'PATCH',
                headers: { 'Content-Type':'application/json', 'Accept':'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ status }),
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.message);
            toast(data.message, 'success');
            document.getElementById('quickStatusSelect').value   = status;
            document.getElementById('modalStatusSelect').value   = status;
        } catch (e) {
            toast(e.message || 'Failed to update status.', 'error');
        } finally {
            btn.disabled            = false;
            btnText.style.display   = 'inline';
            btnSpinner.style.display= 'none';
        }
    }

    document.getElementById('quickUpdateBtn').addEventListener('click', function () {
        const status = document.getElementById('quickStatusSelect').value;
        doStatusUpdate(
            orderId, status,
            document.getElementById('quickUpdateText'),
            document.getElementById('quickUpdateSpinner'),
            this
        );
    });

    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));

    document.getElementById('updateStatusBtn').addEventListener('click', function () {
        document.getElementById('modalOrderNum').textContent = '#' + this.dataset.num;
        document.getElementById('modalStatusSelect').value   = this.dataset.status;
        statusModal.show();
    });

    document.getElementById('saveModalStatusBtn').addEventListener('click', async function () {
        const status = document.getElementById('modalStatusSelect').value;
        await doStatusUpdate(
            orderId, status,
            document.getElementById('saveModalText'),
            document.getElementById('saveModalSpinner'),
            this
        );
        statusModal.hide();
    });
})();
</script>
@endpush
