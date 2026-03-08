@extends('layouts.app')
@section('title', 'My Orders — Shanas')

@section('content')
<style>
:root {
    --pink:      #ff4d6d;
    --pink-d:    #e8304d;
    --pink-soft: #fff0f3;
    --pink-bd:   #ffe4ea;
    --rose:      #ff8fab;
    --page-bg:   #f3f4f6;
    --border:    #e3e6ea;
    --text:      #0f1111;
    --muted:     #6c757d;
    --light:     #f8f9fa;
}

body { background: var(--page-bg); }

/* ── Page layout ── */
.op-wrap { max-width: 1000px; margin: 0 auto; padding: 24px 16px 60px; }

/* ── Page title row ── */
.op-title-row {
    display: flex; align-items: baseline;
    justify-content: space-between; flex-wrap: wrap;
    gap: 8px; margin-bottom: 18px;
}
.op-title-row h1 { font-size: 1.55rem; font-weight: 700; color: var(--text); margin: 0; }
.op-title-row span { font-size: .82rem; color: var(--muted); }

/* ── Filter bar ── */
.op-filter-bar {
    background: white;
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 4px 6px;
    display: flex; gap: 2px;
    overflow-x: auto; scrollbar-width: none;
    margin-bottom: 18px;
    -webkit-overflow-scrolling: touch;
}
.op-filter-bar::-webkit-scrollbar { display: none; }
.op-filter-btn {
    padding: 7px 14px; border-radius: 7px;
    border: none; background: none;
    font-family: inherit; font-size: .78rem; font-weight: 600;
    color: var(--muted); cursor: pointer; white-space: nowrap;
    transition: all .18s; flex-shrink: 0;
}
.op-filter-btn:hover { background: var(--pink-soft); color: var(--pink); }
.op-filter-btn.active { background: var(--pink-soft); color: var(--pink); }
.op-filter-btn .fp {
    display: inline-flex; align-items: center; justify-content: center;
    background: var(--pink); color: white;
    font-size: .58rem; font-weight: 800;
    min-width: 15px; height: 15px;
    border-radius: 8px; padding: 0 4px; margin-left: 4px;
    vertical-align: middle;
}

/* ── Order card — Amazon-style ── */
.o-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
    transition: box-shadow .2s;
}
.o-card:hover { box-shadow: 0 3px 14px rgba(0,0,0,.1); }

/* Card top header — like Amazon's grey bar */
.o-card-top {
    background: #f7f8fa;
    border-bottom: 1px solid var(--border);
    padding: 10px 16px;
    display: flex; align-items: center;
    flex-wrap: wrap; gap: 0;
}
.o-top-col {
    padding: 0 16px 0 0;
    border-right: 1px solid var(--border);
    margin-right: 16px;
    flex-shrink: 0;
}
.o-top-col:last-of-type { border-right: none; margin-right: 0; }
.o-top-col:first-child  { padding-left: 0; }
.o-top-label { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); margin-bottom: 2px; }
.o-top-val   { font-size: .82rem; font-weight: 700; color: var(--text); }
.o-top-val.pink { color: var(--pink); }

.o-top-actions { margin-left: auto; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

/* Status pill */
.st-pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 20px;
    font-size: .68rem; font-weight: 700;
}
.st-pending    { background: #fff5e8; color: #b45309; }
.st-processing { background: #eff6ff; color: #1d4ed8; }
.st-shipped    { background: #f5f3ff; color: #6d28d9; }
.st-delivered  { background: #f0fdf4; color: #15803d; }
.st-cancelled  { background: #fef2f2; color: #b91c1c; }
.st-paid       { background: #f0fdf4; color: #15803d; }
.st-unpaid     { background: #fff5e8; color: #b45309; }
.st-failed     { background: #fef2f2; color: #b91c1c; }

/* ── Tracking bar ── */
.o-track-bar {
    padding: 12px 16px 10px;
    background: var(--pink-soft);
    border-bottom: 1px solid var(--pink-bd);
    display: flex; align-items: center;
}
.trk-node { display: flex; flex-direction: column; align-items: center; flex: 1; position: relative; }
.trk-node:not(:last-child)::after {
    content: ''; position: absolute;
    top: 11px; left: 50%; width: 100%; height: 2px;
    background: var(--pink-bd); z-index: 0;
}
.trk-node.done:not(:last-child)::after  { background: var(--pink); }
.trk-node.current:not(:last-child)::after { background: linear-gradient(90deg, var(--pink) 40%, var(--pink-bd) 40%); }
.trk-dot {
    width: 22px; height: 22px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 9px; border: 2px solid #ddd; background: white;
    position: relative; z-index: 1; flex-shrink: 0; transition: all .3s;
}
.trk-node.done .trk-dot    { background: var(--pink); border-color: var(--pink); color: white; box-shadow: 0 2px 6px rgba(255,77,109,.3); }
.trk-node.current .trk-dot { border-color: var(--pink); color: var(--pink); animation: trkPulse 1.8s infinite; }
.trk-node.waiting .trk-dot { color: #ced4da; }
@keyframes trkPulse {
    0%   { box-shadow: 0 0 0 0 rgba(255,77,109,.35); }
    70%  { box-shadow: 0 0 0 5px rgba(255,77,109,0); }
    100% { box-shadow: 0 0 0 0 rgba(255,77,109,0); }
}
.trk-lbl {
    font-size: .58rem; font-weight: 700; text-align: center;
    color: #adb5bd; text-transform: uppercase; letter-spacing: .02em;
    margin-top: 4px; line-height: 1.2;
}
.trk-node.done .trk-lbl, .trk-node.current .trk-lbl { color: var(--pink); }

.cancelled-notice {
    padding: 10px 16px; background: #fef2f2; border-bottom: 1px solid #fecaca;
    display: flex; align-items: center; gap: 7px;
    font-size: .78rem; font-weight: 600; color: #b91c1c;
}

/* ── Items list ── */
.o-items { padding: 0 16px; }
.o-item {
    display: flex; align-items: flex-start;
    gap: 14px; padding: 14px 0;
    border-bottom: 1px solid #f3f4f6;
}
.o-item:last-child { border-bottom: none; }

.o-item-img {
    width: 70px; height: 70px; border-radius: 8px;
    object-fit: cover; border: 1px solid var(--border);
    flex-shrink: 0; background: var(--light);
}
.o-item-img-ph {
    width: 70px; height: 70px; border-radius: 8px;
    background: var(--pink-soft); border: 1px solid var(--pink-bd);
    display: flex; align-items: center; justify-content: center;
    color: var(--rose); font-size: 22px; flex-shrink: 0;
}
.o-item-info { flex: 1; min-width: 0; }
.o-item-name {
    font-size: .88rem; font-weight: 600; color: var(--text);
    line-height: 1.35; margin-bottom: 3px;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
}
.o-item-sku  { font-size: .7rem; color: var(--muted); font-family: monospace; margin-bottom: 3px; }
.o-item-meta { font-size: .75rem; color: var(--muted); }
.o-item-price { font-size: .9rem; font-weight: 700; color: var(--text); flex-shrink: 0; white-space: nowrap; text-align: right; }
.o-item-sub  { font-size: .7rem; color: var(--muted); text-align: right; margin-top: 2px; }

/* ── Card footer ── */
.o-card-foot {
    background: #fafbfc;
    border-top: 1px solid var(--border);
    padding: 10px 16px;
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap; gap: 10px;
}
.o-foot-total-lbl { font-size: .7rem; color: var(--muted); }
.o-foot-total-val { font-size: 1rem; font-weight: 800; color: var(--text); }
.o-foot-btns { display: flex; gap: 8px; flex-wrap: wrap; }

.btn-primary-sm {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 16px; border-radius: 7px;
    background: linear-gradient(135deg, var(--pink), var(--pink-d));
    color: white; font-size: .75rem; font-weight: 700;
    text-decoration: none; border: none; cursor: pointer;
    font-family: inherit; transition: all .18s;
    box-shadow: 0 2px 8px rgba(255,77,109,.22);
}
.btn-primary-sm:hover { transform: translateY(-1px); box-shadow: 0 5px 14px rgba(255,77,109,.3); color: white; }

.btn-ghost-sm {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 14px; border-radius: 7px;
    background: white; color: var(--muted);
    font-size: .75rem; font-weight: 600;
    border: 1px solid var(--border); cursor: pointer;
    font-family: inherit; transition: all .18s; text-decoration: none;
}
.btn-ghost-sm:hover { border-color: var(--rose); color: var(--pink); background: var(--pink-soft); }

/* ── Address footer ── */
.o-addr-bar {
    padding: 8px 16px; background: #fafbfc;
    border-top: 1px solid #f0f2f4;
    display: flex; align-items: center; gap: 6px;
    font-size: .74rem; color: var(--muted);
    white-space: nowrap; overflow: hidden;
}
.o-addr-bar i { color: var(--rose); flex-shrink: 0; }
.o-addr-bar span { overflow: hidden; text-overflow: ellipsis; }

/* ── Empty ── */
.op-empty {
    background: white; border: 1px solid var(--border);
    border-radius: 10px; text-align: center; padding: 70px 20px;
}
.op-empty .ei { font-size: 3.5rem; opacity: .1; display: block; margin-bottom: 16px; }
.op-empty h4 { font-weight: 700; font-size: 1rem; color: var(--text); margin-bottom: 6px; }
.op-empty p  { font-size: .82rem; color: var(--muted); margin-bottom: 20px; }

@media (max-width: 600px) {
    .o-card-top { flex-direction: column; gap: 8px; }
    .o-top-col  { border-right: none; padding-right: 0; margin-right: 0; }
    .o-top-actions { margin-left: 0; }
    .o-item-img, .o-item-img-ph { width: 54px; height: 54px; }
    .op-title-row h1 { font-size: 1.25rem; }
}
</style>

<div class="op-wrap">

    {{-- Title --}}
    <div class="op-title-row">
        <h1>Your Orders</h1>
        <span>{{ $orders->total() }} order{{ $orders->total() !== 1 ? 's' : '' }}</span>
    </div>

    {{-- Filter bar --}}
    <div class="op-filter-bar">
        @php
            $tabs = [
                'all'        => 'All Orders',
                'pending'    => 'Pending',
                'processing' => 'Processing',
                'shipped'    => 'Shipped',
                'delivered'  => 'Delivered',
                'cancelled'  => 'Cancelled',
            ];
            $currentFilter = request('filter', 'all');
        @endphp
        @foreach($tabs as $key => $label)
        <button class="op-filter-btn {{ $currentFilter === $key ? 'active' : '' }}"
                data-filter="{{ $key }}"
                onclick="filterOrders('{{ $key }}', this)">
            {{ $label }}
            @if($key !== 'all' && isset($counts[$key]) && $counts[$key] > 0)
                <span class="fp">{{ $counts[$key] }}</span>
            @endif
        </button>
        @endforeach
    </div>

    {{-- Orders --}}
    <div id="ordersContainer">
        @forelse($orders as $order)
        @php
            $stepMap = ['pending' => 0, 'processing' => 1, 'shipped' => 2, 'delivered' => 3];
            $cur     = $stepMap[$order->status] ?? 0;
            $steps   = [
                ['icon' => 'bi-bag-check-fill', 'label' => 'Placed'],
                ['icon' => 'bi-gear-fill',       'label' => 'Processing'],
                ['icon' => 'bi-truck',           'label' => 'Shipped'],
                ['icon' => 'bi-house-check-fill','label' => 'Delivered'],
            ];
        @endphp

        <div class="o-card" data-status="{{ $order->status }}">

            {{-- ── Amazon-style grey top bar ── --}}
            <div class="o-card-top">
                <div class="o-top-col">
                    <div class="o-top-label">Order Placed</div>
                    <div class="o-top-val">{{ $order->created_at->format('d M Y') }}</div>
                </div>
                <div class="o-top-col">
                    <div class="o-top-label">Total</div>
                    <div class="o-top-val">₹{{ number_format($order->total_amount) }}</div>
                </div>
                <div class="o-top-col">
                    <div class="o-top-label">Payment</div>
                    <div class="o-top-val">
                        <span class="st-pill st-{{ $order->payment_status === 'paid' ? 'paid' : ($order->payment_status === 'failed' ? 'failed' : 'unpaid') }}">
                            <i class="bi {{ $order->payment_status === 'paid' ? 'bi-shield-check' : 'bi-clock' }}"></i>
                            {{ ucfirst($order->payment_status ?? 'pending') }}
                        </span>
                    </div>
                </div>
                <div class="o-top-col" style="flex:1;min-width:0;">
                    <div class="o-top-label">Order #</div>
                    <div class="o-top-val pink" style="font-family:monospace;font-size:.78rem;">{{ $order->order_number }}</div>
                </div>
                <div class="o-top-actions">
                    <a href="{{ route('account.order.detail', $order->id) }}" class="btn-primary-sm">
                        View Order <i class="bi bi-arrow-right"></i>
                    </a>
                    @if($order->status === 'delivered')
                    <button class="btn-ghost-sm">
                        <i class="bi bi-arrow-clockwise"></i> Reorder
                    </button>
                    @endif
                </div>
            </div>

            {{-- ── Tracking bar ── --}}
            @if($order->status !== 'cancelled')
            <div class="o-track-bar">
                <div style="font-size:.72rem;font-weight:700;color:var(--pink);white-space:nowrap;margin-right:14px;flex-shrink:0;">
                    @if($order->status === 'pending')        <i class="bi bi-hourglass-split me-1"></i>Awaiting confirmation
                    @elseif($order->status === 'processing') <i class="bi bi-gear-fill me-1"></i>Being packed
                    @elseif($order->status === 'shipped')    <i class="bi bi-truck me-1"></i>On the way
                    @elseif($order->status === 'delivered')  <i class="bi bi-check-circle-fill me-1"></i>Delivered
                    @endif
                </div>
                <div style="flex:1;display:flex;">
                    @foreach($steps as $i => $step)
                    @php $cls = $i < $cur ? 'done' : ($i === $cur ? 'current' : 'waiting'); @endphp
                    <div class="trk-node {{ $cls }}">
                        <div class="trk-dot">
                            @if($i < $cur) <i class="bi bi-check-lg"></i>
                            @else          <i class="bi {{ $step['icon'] }}"></i>
                            @endif
                        </div>
                        <div class="trk-lbl">{{ $step['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="cancelled-notice">
                <i class="bi bi-x-circle-fill"></i>
                Order cancelled &nbsp;·&nbsp; If you were charged, a refund will be processed within 5–7 business days.
            </div>
            @endif

            {{-- ── Items ── --}}
            <div class="o-items">
                @forelse($order->items as $item)
                <div class="o-item">
                    {{-- Image --}}
                    @if($item->product_image)
                        <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}"
                             class="o-item-img"
                             onerror="this.outerHTML='<div class=\'o-item-img-ph\'><i class=\'bi bi-box-seam\'></i></div>'">
                    @else
                        <div class="o-item-img-ph"><i class="bi bi-box-seam"></i></div>
                    @endif

                    {{-- Info --}}
                    <div class="o-item-info">
                        <div class="o-item-name">{{ $item->product_name }}</div>
                        @if($item->product_sku)
                        <div class="o-item-sku">SKU: {{ $item->product_sku }}</div>
                        @endif
                        <div class="o-item-meta">
                            Qty: {{ $item->quantity }}
                            &nbsp;·&nbsp;
                            ₹{{ number_format($item->unit_price) }} each
                        </div>
                    </div>

                    {{-- Price --}}
                    <div>
                        <div class="o-item-price">₹{{ number_format($item->subtotal) }}</div>
                        <div class="o-item-sub">subtotal</div>
                    </div>
                </div>
                @empty
                <div style="padding:16px 0;font-size:.82rem;color:var(--muted);text-align:center;">
                    No item details available.
                </div>
                @endforelse
            </div>

            {{-- ── Address ── --}}
            @if($order->shipping_address)
            <div class="o-addr-bar">
                <i class="bi bi-geo-alt-fill"></i>
                <span>
                    Ship to:&nbsp;
                    @if(is_array($order->shipping_address))
                        {{ implode(', ', array_filter($order->shipping_address)) }}
                    @else
                        {{ $order->shipping_address }}
                    @endif
                </span>
            </div>
            @endif

            {{-- ── Footer total ── --}}
            <div class="o-card-foot">
                <div>
                    <div class="o-foot-total-lbl">Order Total ({{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }})</div>
                    <div class="o-foot-total-val">₹{{ number_format($order->total_amount) }}</div>
                </div>
                <div class="o-foot-btns">
                    <span class="st-pill st-{{ $order->status }}">
                        @if($order->status==='pending')        <i class="bi bi-hourglass-split"></i>
                        @elseif($order->status==='processing') <i class="bi bi-gear-fill"></i>
                        @elseif($order->status==='shipped')    <i class="bi bi-truck"></i>
                        @elseif($order->status==='delivered')  <i class="bi bi-check-circle-fill"></i>
                        @elseif($order->status==='cancelled')  <i class="bi bi-x-circle-fill"></i>
                        @endif
                        {{ ucfirst($order->status) }}
                    </span>
                    <a href="{{ route('account.order.detail', $order->id) }}" class="btn-ghost-sm">
                        <i class="bi bi-file-text"></i> Order Details
                    </a>
                </div>
            </div>

        </div>
        @empty

        <div class="op-empty" id="emptyState">
            <span class="ei">🛍️</span>
            <h4>No orders yet</h4>
            <p>You haven't placed any orders.<br>Explore our collection and find something you love!</p>
            <a href="{{ route('products.index') }}"
               style="display:inline-flex;align-items:center;gap:6px;padding:9px 22px;border-radius:8px;background:linear-gradient(135deg,var(--pink),var(--pink-d));color:white;font-weight:700;text-decoration:none;font-size:.82rem;box-shadow:0 4px 14px rgba(255,77,109,.26);">
                Shop Now <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        @endforelse
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div style="display:flex;justify-content:center;margin-top:10px;">
        {{ $orders->appends(request()->query())->links() }}
    </div>
    @endif

</div>

<script>
function filterOrders(filter, btn) {
    document.querySelectorAll('.op-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const cards = document.querySelectorAll('.o-card');
    let visible = 0;
    cards.forEach(card => {
        const show = filter === 'all' || card.dataset.status === filter;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    let emptyEl = document.getElementById('emptyState');
    if (!emptyEl && visible === 0) {
        emptyEl = document.createElement('div');
        emptyEl.id = 'emptyState';
        emptyEl.className = 'op-empty';
        emptyEl.innerHTML = `
            <span class="ei">📦</span>
            <h4>No ${filter} orders</h4>
            <p>No orders found for this status.</p>`;
        document.getElementById('ordersContainer').appendChild(emptyEl);
    } else if (emptyEl) {
        emptyEl.style.display = visible === 0 ? '' : 'none';
    }
}

// function reorder(orderId) {
//     alert('Reorder feature coming soon!');
// }
</script>

@endsection
