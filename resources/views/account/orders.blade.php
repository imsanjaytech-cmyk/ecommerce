@extends('layouts.app')
@section('title', 'My Orders — Shanas')

@section('content')

<style>
.orders-page { background: #f7f8fc; min-height: 100vh; padding-bottom: 60px; }

/* ── Sticky top bar ── */
.orders-top {
    background: white;
    border-bottom: 1.5px solid #eef0f4;
    padding: 16px 0 0;
    position: sticky;
    top: 0;
    z-index: 50;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}
.orders-top-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}
.orders-top h1 { font-size: 1.2rem; font-weight: 800; color: #1a1a1a; margin: 0; }
.orders-top-sub { font-size: .75rem; color: #b0bec5; margin-top: 1px; }

/* ── Tabs ── */
.order-tabs {
    display: flex;
    overflow-x: auto;
    scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
    margin-top: 12px;
    gap: 0;
}
.order-tabs::-webkit-scrollbar { display: none; }
.order-tab-btn {
    padding: 9px 15px;
    border: none; background: none;
    font-family: inherit; font-size: .78rem; font-weight: 600;
    color: #9199a6; cursor: pointer; white-space: nowrap;
    transition: color .2s;
    border-bottom: 2.5px solid transparent;
    margin-bottom: -1.5px; flex-shrink: 0;
}
.order-tab-btn:hover { color: #ff4d6d; }
.order-tab-btn.active { color: #ff4d6d; border-bottom-color: #ff4d6d; }
.tab-pill {
    display: inline-flex; align-items: center; justify-content: center;
    background: #ff4d6d; color: white;
    font-size: .58rem; font-weight: 800;
    min-width: 15px; height: 15px;
    border-radius: 8px; padding: 0 4px;
    margin-left: 4px; vertical-align: middle;
}

/* ── Feed ── */
.orders-feed { padding-top: 18px; }

/* ── Card ── */
.order-card {
    background: white;
    border-radius: 14px;
    border: 1.5px solid #eef0f4;
    overflow: hidden;
    margin-bottom: 12px;
    box-shadow: 0 1px 5px rgba(0,0,0,.04);
    transition: box-shadow .2s, transform .2s;
}
.order-card:hover {
    box-shadow: 0 5px 20px rgba(255,77,109,.08);
    transform: translateY(-1px);
}

/* Header */
.oc-head {
    display: flex; align-items: center;
    padding: 11px 14px; gap: 8px; flex-wrap: wrap;
    border-bottom: 1px solid #f4f5f8;
}
.oc-num  { font-weight: 800; font-size: .85rem; color: #ff4d6d; }
.oc-date { font-size: .7rem; color: #b0bec5; margin-top: 1px; }
.oc-chips{ display: flex; align-items: center; gap: 5px; margin-left: auto; flex-wrap: wrap; }

.chip {
    display: inline-flex; align-items: center; gap: 3px;
    padding: 2px 9px; border-radius: 20px;
    font-size: .68rem; font-weight: 700;
}
.chip-pending    { background: #fff5e8; color: #d97706; }
.chip-processing { background: #e8f4ff; color: #1a7cd4; }
.chip-shipped    { background: #f0eeff; color: #7c3aed; }
.chip-delivered  { background: #e8f8ee; color: #1f9c4a; }
.chip-cancelled  { background: #fee8eb; color: #dc3545; }
.chip-paid       { background: #e8f8ee; color: #1f9c4a; }
.chip-unpaid     { background: #fff5e8; color: #d97706; }
.chip-failed     { background: #fee8eb; color: #dc3545; }

/* ── Tracking ── */
.oc-track {
    padding: 12px 14px 8px;
    background: #fcfcfd;
    border-bottom: 1px solid #f4f5f8;
}
.track-row { display: flex; align-items: flex-start; }
.track-node {
    display: flex; flex-direction: column; align-items: center;
    flex: 1; position: relative;
}
.track-node:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 13px; left: 50%;
    width: 100%; height: 2px;
    background: #e8eaee; z-index: 0;
}
.track-node.done:not(:last-child)::after {
    background: linear-gradient(90deg, #ff4d6d, #ff8fab);
}
.track-node.current:not(:last-child)::after {
    background: linear-gradient(90deg, #ff4d6d 50%, #e8eaee 50%);
}
.track-dot {
    width: 26px; height: 26px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 10px;
    border: 2px solid #dde0e8;
    background: white;
    position: relative; z-index: 1;
    transition: all .3s; flex-shrink: 0;
}
.track-node.done .track-dot {
    background: linear-gradient(135deg, #ff4d6d, #ff8fab);
    border-color: #ff4d6d; color: white;
    box-shadow: 0 2px 8px rgba(255,77,109,.28);
}
.track-node.current .track-dot {
    border-color: #ff4d6d; color: #ff4d6d;
    animation: nodePulse 1.8s infinite;
}
.track-node.waiting .track-dot { color: #ced2d9; }
@keyframes nodePulse {
    0%   { box-shadow: 0 0 0 0 rgba(255,77,109,.35); }
    70%  { box-shadow: 0 0 0 6px rgba(255,77,109,0); }
    100% { box-shadow: 0 0 0 0 rgba(255,77,109,0); }
}
.track-lbl {
    font-size: .58rem; font-weight: 700;
    color: #c4c9d4; text-align: center;
    text-transform: uppercase; letter-spacing: .02em;
    margin-top: 4px; line-height: 1.2;
}
.track-node.done .track-lbl,
.track-node.current .track-lbl { color: #ff4d6d; }

/* Cancelled */
.cancelled-bar {
    padding: 9px 14px;
    background: #fff5f5; border-bottom: 1px solid #fee8eb;
    display: flex; align-items: center; gap: 6px;
    font-size: .75rem; font-weight: 600; color: #dc3545;
}

/* ── Items ── */
.oc-items {
    padding: 9px 14px;
    display: flex; align-items: center; gap: 6px;
    border-bottom: 1px solid #f4f5f8; flex-wrap: wrap;
}
.oc-thumb {
    width: 40px; height: 40px;
    border-radius: 8px; object-fit: cover;
    border: 1.5px solid #eef0f4; flex-shrink: 0;
}
.oc-thumb-ph {
    width: 40px; height: 40px;
    border-radius: 8px; background: #fff0f3;
    border: 1.5px solid #ffe4ea; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    color: #ff8fab; font-size: 14px;
}
.oc-more {
    width: 40px; height: 40px;
    border-radius: 8px; background: #f5f6fb;
    border: 1.5px dashed #dde0e8; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: .65rem; font-weight: 700; color: #9199a6;
}
.oc-icount { margin-left: auto; font-size: .7rem; color: #b0bec5; }

/* ── Address ── */
.oc-addr {
    padding: 7px 14px;
    display: flex; align-items: center; gap: 6px;
    font-size: .72rem; color: #6c757d;
    border-bottom: 1px solid #f4f5f8;
    background: #fafbfc;
    white-space: nowrap; overflow: hidden;
}
.oc-addr i { color: #ff8fab; flex-shrink: 0; font-size: .78rem; }
.oc-addr span { overflow: hidden; text-overflow: ellipsis; }

/* ── Footer ── */
.oc-foot {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 9px 14px; gap: 8px; flex-wrap: wrap;
}
.oc-tot-lbl { font-size: .68rem; color: #9199a6; }
.oc-tot-val { font-size: .9rem; font-weight: 800; color: #1a1a1a; margin-top: 1px; }

.btn-view {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 14px; border-radius: 8px;
    background: linear-gradient(135deg, #ff4d6d, #e8304d);
    color: white; font-size: .73rem; font-weight: 700;
    text-decoration: none; border: none; cursor: pointer;
    transition: all .2s; font-family: inherit;
    box-shadow: 0 3px 10px rgba(255,77,109,.2);
}
.btn-view:hover { transform: translateY(-1px); box-shadow: 0 5px 16px rgba(255,77,109,.3); color: white; }

.btn-reorder {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 12px; border-radius: 8px;
    background: white; color: #6c757d;
    font-size: .73rem; font-weight: 600;
    border: 1.5px solid #eef0f4; cursor: pointer;
    transition: all .2s; font-family: inherit;
}
.btn-reorder:hover { border-color: #ff8fab; color: #ff4d6d; background: #fff0f3; }

/* ── Empty ── */
.orders-empty {
    text-align: center; padding: 60px 20px;
    background: white; border-radius: 14px;
    border: 1.5px solid #eef0f4;
}
.orders-empty .ei { font-size: 3rem; opacity: .1; display: block; margin-bottom: 14px; }
.orders-empty h5 { font-weight: 700; color: #1a1a1a; margin-bottom: 5px; font-size: .95rem; }
.orders-empty p  { color: #9199a6; font-size: .8rem; margin-bottom: 18px; }

@media (max-width: 576px) {
    .orders-top h1 { font-size: 1.05rem; }
    .oc-head { padding: 9px 12px; }
    .oc-track { padding: 10px 12px 6px; }
    .oc-items, .oc-foot { padding: 8px 12px; }
    .track-dot { width: 22px; height: 22px; font-size: 9px; }
    .track-lbl { font-size: .52rem; }
}
</style>

<div class="orders-page">

{{-- ── Sticky header + tabs ── --}}
<div class="orders-top">
    <div class="container">
        <div class="orders-top-inner">
            <div>
                <h1><i class="bi bi-bag-heart me-2" style="color:#ff4d6d;font-size:1rem;"></i>My Orders</h1>
                <div class="orders-top-sub">{{ $orders->total() }} order{{ $orders->total() !== 1 ? 's' : '' }} total</div>
            </div>
            <a href="{{ route('products.index') }}"
               style="display:inline-flex;align-items:center;gap:5px;padding:6px 13px;border-radius:8px;background:#fff0f3;color:#ff4d6d;font-size:.73rem;font-weight:700;text-decoration:none;border:1.5px solid #ffe4ea;">
                <i class="bi bi-bag-plus"></i> Shop More
            </a>
        </div>

        <div class="order-tabs">
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
            <button class="order-tab-btn {{ $currentFilter === $key ? 'active' : '' }}"
                    data-filter="{{ $key }}"
                    onclick="filterOrders('{{ $key }}', this)">
                {{ $label }}
                @if($key !== 'all' && isset($counts[$key]) && $counts[$key] > 0)
                    <span class="tab-pill">{{ $counts[$key] }}</span>
                @endif
            </button>
            @endforeach
        </div>
    </div>
</div>

{{-- ── Cards ── --}}
<div class="container orders-feed">
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

        <div class="order-card" data-status="{{ $order->status }}">

            {{-- Header --}}
            <div class="oc-head">
                <div>
                    <div class="oc-num">#{{ $order->order_number }}</div>
                    <div class="oc-date"><i class="bi bi-calendar3"></i> {{ $order->created_at->format('d M Y, h:i A') }}</div>
                </div>
                <div class="oc-chips">
                    <span class="chip chip-{{ $order->payment_status === 'paid' ? 'paid' : ($order->payment_status === 'failed' ? 'failed' : 'unpaid') }}">
                        <i class="bi {{ $order->payment_status === 'paid' ? 'bi-shield-check' : 'bi-clock' }}"></i>
                        {{ ucfirst($order->payment_status ?? 'pending') }}
                    </span>
                    <span class="chip chip-{{ $order->status }}">
                        @if($order->status==='pending')        <i class="bi bi-hourglass-split"></i>
                        @elseif($order->status==='processing') <i class="bi bi-gear-fill"></i>
                        @elseif($order->status==='shipped')    <i class="bi bi-truck"></i>
                        @elseif($order->status==='delivered')  <i class="bi bi-check-circle-fill"></i>
                        @elseif($order->status==='cancelled')  <i class="bi bi-x-circle-fill"></i>
                        @endif
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            {{-- Tracking or cancelled bar --}}
            @if($order->status !== 'cancelled')
            <div class="oc-track">
                <div class="track-row">
                    @foreach($steps as $i => $step)
                    @php $cls = $i < $cur ? 'done' : ($i === $cur ? 'current' : 'waiting'); @endphp
                    <div class="track-node {{ $cls }}">
                        <div class="track-dot">
                            @if($i < $cur) <i class="bi bi-check-lg"></i>
                            @else          <i class="bi {{ $step['icon'] }}"></i>
                            @endif
                        </div>
                        <div class="track-lbl">{{ $step['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="cancelled-bar">
                <i class="bi bi-x-circle-fill"></i> This order was cancelled
            </div>
            @endif

            {{-- Items --}}
            @if($order->items && $order->items->count())
            <div class="oc-items">
                @foreach($order->items->take(6) as $item)
                    @if($item->product_image)
                        <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}"
                             class="oc-thumb" title="{{ $item->product_name }} × {{ $item->quantity }}"
                             onerror="this.outerHTML='<div class=\'oc-thumb-ph\'><i class=\'bi bi-box-seam\'></i></div>'">
                    @else
                        <div class="oc-thumb-ph" title="{{ $item->product_name }}"><i class="bi bi-box-seam"></i></div>
                    @endif
                @endforeach
                @if($order->items->count() > 6)
                <div class="oc-more">+{{ $order->items->count() - 6 }}</div>
                @endif
                <span class="oc-icount">{{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}</span>
            </div>
            @endif

            {{-- Address --}}
            @if($order->shipping_address)
            <div class="oc-addr">
                <i class="bi bi-geo-alt-fill"></i>
                <span>
                    @if(is_array($order->shipping_address))
                        {{ implode(', ', array_filter($order->shipping_address)) }}
                    @else
                        {{ $order->shipping_address }}
                    @endif
                </span>
            </div>
            @endif

            {{-- Footer --}}
            <div class="oc-foot">
                <div>
                    <div class="oc-tot-lbl">Order Total</div>
                    <div class="oc-tot-val">₹{{ number_format($order->total_amount) }}</div>
                </div>
                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                    @if($order->status === 'delivered')
                    <button class="btn-reorder" onclick="reorder({{ $order->id }})">
                        <i class="bi bi-arrow-clockwise"></i> Reorder
                    </button>
                    @endif
                    <a href="{{ route('account.order.detail', $order->id) }}" class="btn-view">
                        View Details <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

        </div>
        @empty

        <div class="orders-empty" id="emptyState">
            <span class="ei">🛍️</span>
            <h5>No orders yet</h5>
            <p>You haven't placed any orders.<br>Explore our collection!</p>
            <a href="{{ route('products.index') }}"
               style="display:inline-flex;align-items:center;gap:6px;padding:9px 22px;border-radius:9px;background:linear-gradient(135deg,#ff4d6d,#e8304d);color:white;font-weight:700;text-decoration:none;font-size:.8rem;box-shadow:0 4px 14px rgba(255,77,109,.26);">
                Shop Now <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        @endforelse
    </div>

    @if($orders->hasPages())
    <div style="display:flex;justify-content:center;margin-top:4px;padding-bottom:10px;">
        {{ $orders->appends(request()->query())->links() }}
    </div>
    @endif
</div>

</div>

<script>
function filterOrders(filter, btn) {
    document.querySelectorAll('.order-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const cards = document.querySelectorAll('.order-card');
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
        emptyEl.className = 'orders-empty';
        emptyEl.innerHTML = `<span class="ei">📦</span><h5>No ${filter} orders</h5><p>No orders found for this status.</p>`;
        document.getElementById('ordersContainer').appendChild(emptyEl);
    } else if (emptyEl) {
        emptyEl.style.display = visible === 0 ? '' : 'none';
    }
}

function reorder(orderId) {
    alert('Reorder feature coming soon!');
}
</script>

@endsection
