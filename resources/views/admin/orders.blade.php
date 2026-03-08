@extends('layouts.app')
@section('title', 'My Orders — Shanas')

@section('content')

<style>
.orders-hero {
    background: linear-gradient(135deg, #fff0f3 0%, #ffeef5 50%, #f0f4ff 100%);
    padding: 48px 0 32px;
    margin-bottom: 0;
}

.orders-hero h1 {
    font-size: 2rem;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 4px;
}

.orders-hero p {
    color: #9199a6;
    font-size: .9rem;
}

/* ── Tab bar ── */
.order-tabs {
    display: flex;
    gap: 6px;
    overflow-x: auto;
    padding: 0 0 2px;
    scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
    border-bottom: 2px solid #f0f0f0;
    margin-bottom: 28px;
}
.order-tabs::-webkit-scrollbar { display: none; }

.order-tab-btn {
    padding: 9px 18px;
    border-radius: 10px 10px 0 0;
    border: none;
    background: none;
    font-family: inherit;
    font-size: .82rem;
    font-weight: 600;
    color: #9199a6;
    cursor: pointer;
    white-space: nowrap;
    transition: all .2s;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
}
.order-tab-btn:hover { color: #ff4d6d; }
.order-tab-btn.active {
    color: #ff4d6d;
    border-bottom-color: #ff4d6d;
    background: #fff0f3;
}
.tab-count-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #ff4d6d;
    color: white;
    font-size: .65rem;
    font-weight: 700;
    min-width: 18px;
    height: 18px;
    border-radius: 9px;
    padding: 0 5px;
    margin-left: 5px;
    vertical-align: middle;
}

/* ── Order Card ── */
.order-card {
    background: white;
    border-radius: 18px;
    border: 1.5px solid #eef0f4;
    overflow: hidden;
    margin-bottom: 18px;
    box-shadow: 0 2px 12px rgba(0,0,0,.04);
    transition: box-shadow .25s, transform .25s;
}
.order-card:hover {
    box-shadow: 0 8px 30px rgba(255,77,109,.1);
    transform: translateY(-1px);
}

.order-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    background: #fafbfc;
    border-bottom: 1.5px solid #eef0f4;
    flex-wrap: wrap;
    gap: 10px;
}

.order-num {
    font-weight: 800;
    font-size: .95rem;
    color: #ff4d6d;
}
.order-date {
    font-size: .78rem;
    color: #9199a6;
    margin-top: 1px;
}

.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 13px;
    border-radius: 20px;
    font-size: .75rem;
    font-weight: 700;
}
.status-pending    { background: #fff5e8; color: #d97706; }
.status-processing { background: #e8f4ff; color: #1a7cd4; }
.status-shipped    { background: #f0eeff; color: #7c3aed; }
.status-delivered  { background: #e8f8ee; color: #1f9c4a; }
.status-cancelled  { background: #fee8eb; color: #dc3545; }

/* ── Tracking Timeline ── */
.tracking-wrap {
    padding: 20px 20px 10px;
}

.tracking-steps {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    position: relative;
    margin-bottom: 10px;
}

/* Connecting line behind steps */
.tracking-steps::before {
    content: '';
    position: absolute;
    top: 18px;
    left: calc(10% + 18px);
    right: calc(10% + 18px);
    height: 3px;
    background: #eef0f4;
    z-index: 0;
}

/* Filled progress line */
.tracking-steps .progress-line {
    position: absolute;
    top: 18px;
    left: calc(10% + 18px);
    height: 3px;
    background: linear-gradient(90deg, #ff4d6d, #ff8fab);
    z-index: 1;
    transition: width .6s ease;
}

.track-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    width: 20%;
    position: relative;
    z-index: 2;
}

.track-icon {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    border: 3px solid #eef0f4;
    background: white;
    transition: all .3s;
    flex-shrink: 0;
}
.track-step.done .track-icon {
    background: linear-gradient(135deg, #ff4d6d, #ff8fab);
    border-color: #ff4d6d;
    color: white;
    box-shadow: 0 4px 14px rgba(255,77,109,.35);
}
.track-step.current .track-icon {
    border-color: #ff4d6d;
    color: #ff4d6d;
    animation: pulseRing 1.8s infinite;
}
.track-step.pending-step .track-icon {
    color: #c4c9d4;
}

@keyframes pulseRing {
    0%   { box-shadow: 0 0 0 0 rgba(255,77,109,.4); }
    70%  { box-shadow: 0 0 0 8px rgba(255,77,109,0); }
    100% { box-shadow: 0 0 0 0 rgba(255,77,109,0); }
}

.track-label {
    font-size: .68rem;
    font-weight: 700;
    text-align: center;
    color: #9199a6;
    line-height: 1.3;
    text-transform: uppercase;
    letter-spacing: .03em;
}
.track-step.done .track-label,
.track-step.current .track-label { color: #ff4d6d; }

.track-time {
    font-size: .62rem;
    color: #c4c9d4;
    text-align: center;
}
.track-step.done .track-time { color: #b0bec5; }

/* ── Order items inside card ── */
.order-items-wrap {
    padding: 16px 20px;
    border-top: 1.5px solid #eef0f4;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

.order-item-thumb {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    object-fit: cover;
    border: 1.5px solid #eef0f4;
    background: #fafbfc;
    flex-shrink: 0;
}
.order-item-thumb-placeholder {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    background: #fff0f3;
    border: 1.5px solid #ffe4ea;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ff8fab;
    font-size: 18px;
    flex-shrink: 0;
}

.more-items-badge {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    background: #f5f6fb;
    border: 1.5px dashed #eef0f4;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .72rem;
    font-weight: 700;
    color: #9199a6;
    flex-shrink: 0;
}

/* ── Card footer ── */
.order-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    border-top: 1.5px solid #eef0f4;
    flex-wrap: wrap;
    gap: 10px;
}

.order-total-label { font-size: .78rem; color: #9199a6; }
.order-total-val   { font-size: 1.05rem; font-weight: 800; color: #1a1a1a; }

.btn-view-order {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    border-radius: 10px;
    background: linear-gradient(135deg, #ff4d6d, #e8304d);
    color: white;
    font-size: .8rem;
    font-weight: 700;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all .2s;
    box-shadow: 0 4px 14px rgba(255,77,109,.25);
}
.btn-view-order:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 22px rgba(255,77,109,.35);
    color: white;
}

.btn-reorder {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 10px;
    background: white;
    color: #6c757d;
    font-size: .8rem;
    font-weight: 600;
    text-decoration: none;
    border: 1.5px solid #eef0f4;
    cursor: pointer;
    transition: all .2s;
}
.btn-reorder:hover {
    border-color: #ff8fab;
    color: #ff4d6d;
    background: #fff0f3;
}

/* ── Empty state ── */
.empty-orders {
    text-align: center;
    padding: 80px 20px;
}
.empty-orders .empty-icon {
    font-size: 4.5rem;
    opacity: .12;
    display: block;
    margin-bottom: 20px;
    line-height: 1;
}
.empty-orders h4 { font-weight: 700; color: #1a1a1a; margin-bottom: 8px; }
.empty-orders p  { color: #9199a6; font-size: .88rem; }

/* ── Address chip ── */
.address-chip {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: #f5f6fb;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: .78rem;
    color: #6c757d;
    line-height: 1.5;
    margin-top: 10px;
    border: 1px solid #eef0f4;
}
.address-chip i { color: #ff4d6d; margin-top: 2px; flex-shrink: 0; }

/* ── Payment chip ── */
.pay-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px;
    border-radius: 7px;
    font-size: .72rem;
    font-weight: 700;
}
.pay-paid   { background: #e8f8ee; color: #1f9c4a; }
.pay-pending{ background: #fff5e8; color: #d97706; }
.pay-failed { background: #fee8eb; color: #dc3545; }

@media (max-width: 576px) {
    .orders-hero h1 { font-size: 1.5rem; }
    .tracking-steps::before,
    .tracking-steps .progress-line { display: none; }
    .tracking-steps { gap: 6px; }
    .track-icon { width: 30px; height: 30px; font-size: 11px; }
    .order-card-header { flex-direction: column; align-items: flex-start; }
}
</style>

{{-- ── Hero ── --}}
<div class="orders-hero">
    <div class="container">
        <h1>My Orders</h1>
        <p>Track, manage and reorder your purchases</p>
    </div>
</div>

<div class="container py-4">

    {{-- ── Tabs ── --}}
    <div class="order-tabs" id="orderTabs">
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
            @if(isset($counts[$key]) && $counts[$key] > 0)
                <span class="tab-count-pill">{{ $counts[$key] }}</span>
            @endif
        </button>
        @endforeach
    </div>

    {{-- ── Orders List ── --}}
    <div id="ordersContainer">
        @forelse($orders as $order)
        @php
            $statusMap = [
                'pending'    => 0,
                'processing' => 1,
                'shipped'    => 2,
                'delivered'  => 3,
                'cancelled'  => -1,
            ];
            $currentStep = $statusMap[$order->status] ?? 0;

            $steps = [
                ['icon' => 'bi-bag-check',      'label' => 'Order\nPlaced',  'key' => 'pending'],
                ['icon' => 'bi-gear',            'label' => 'Processing',     'key' => 'processing'],
                ['icon' => 'bi-truck',           'label' => 'Shipped',        'key' => 'shipped'],
                ['icon' => 'bi-house-check',     'label' => 'Delivered',      'key' => 'delivered'],
            ];

            // Progress line width (0%, 33%, 66%, 100%)
            $progressPct = $order->status === 'cancelled' ? 0 : ($currentStep / 3 * 100);
        @endphp

        <div class="order-card" data-status="{{ $order->status }}">

            {{-- Header --}}
            <div class="order-card-header">
                <div>
                    <div class="order-num">#{{ $order->order_number }}</div>
                    <div class="order-date">
                        <i class="bi bi-calendar3" style="font-size:.7rem;"></i>
                        {{ $order->created_at->format('d M Y, h:i A') }}
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    {{-- Payment status chip --}}
                    <span class="pay-chip {{ $order->payment_status === 'paid' ? 'pay-paid' : ($order->payment_status === 'failed' ? 'pay-failed' : 'pay-pending') }}">
                        <i class="bi {{ $order->payment_status === 'paid' ? 'bi-shield-check' : 'bi-clock' }}"></i>
                        {{ ucfirst($order->payment_status ?? 'pending') }}
                    </span>

                    {{-- Order status pill --}}
                    <span class="status-pill status-{{ $order->status }}">
                        @if($order->status === 'pending')    <i class="bi bi-hourglass-split"></i>
                        @elseif($order->status === 'processing') <i class="bi bi-gear-fill"></i>
                        @elseif($order->status === 'shipped')    <i class="bi bi-truck"></i>
                        @elseif($order->status === 'delivered')  <i class="bi bi-check-circle-fill"></i>
                        @elseif($order->status === 'cancelled')  <i class="bi bi-x-circle-fill"></i>
                        @endif
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            {{-- Tracking Timeline (hidden for cancelled) --}}
            @if($order->status !== 'cancelled')
            <div class="tracking-wrap">
                <div class="tracking-steps" id="track-{{ $order->id }}">
                    <div class="progress-line" style="width: {{ $progressPct }}%;"></div>

                    @foreach($steps as $i => $step)
                    @php
                        $stepClass = '';
                        if ($i < $currentStep)       $stepClass = 'done';
                        elseif ($i === $currentStep)  $stepClass = 'current';
                        else                          $stepClass = 'pending-step';
                    @endphp
                    <div class="track-step {{ $stepClass }}">
                        <div class="track-icon">
                            @if($i < $currentStep)
                                <i class="bi bi-check-lg"></i>
                            @else
                                <i class="bi {{ $step['icon'] }}"></i>
                            @endif
                        </div>
                        <div class="track-label">{{ $step['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            {{-- Cancelled notice --}}
            <div style="padding:14px 20px;background:#fff5f5;border-top:1.5px solid #fee8eb;">
                <div style="display:flex;align-items:center;gap:8px;font-size:.82rem;color:#dc3545;font-weight:600;">
                    <i class="bi bi-x-circle-fill"></i>
                    This order was cancelled.
                </div>
            </div>
            @endif

            {{-- Items thumbnails --}}
            @if($order->items && $order->items->count())
            <div class="order-items-wrap">
                @foreach($order->items->take(4) as $item)
                    @if($item->product_image)
                        <img src="{{ $item->product_image }}"
                             alt="{{ $item->product_name }}"
                             class="order-item-thumb"
                             title="{{ $item->product_name }} × {{ $item->quantity }}"
                             onerror="this.outerHTML='<div class=\'order-item-thumb-placeholder\'><i class=\'bi bi-box-seam\'></i></div>'">
                    @else
                        <div class="order-item-thumb-placeholder" title="{{ $item->product_name }}">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    @endif
                @endforeach

                @if($order->items->count() > 4)
                <div class="more-items-badge">+{{ $order->items->count() - 4 }}</div>
                @endif

                <div style="margin-left:auto;font-size:.78rem;color:#9199a6;">
                    {{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}
                </div>
            </div>
            @endif

            {{-- Shipping address --}}
            @if($order->shipping_address)
            <div style="padding:0 20px 14px;">
                <div class="address-chip">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>
                        @if(is_array($order->shipping_address))
                            {{ implode(', ', array_filter($order->shipping_address)) }}
                        @else
                            {{ $order->shipping_address }}
                        @endif
                    </span>
                </div>
            </div>
            @endif

            {{-- Footer --}}
            <div class="order-card-footer">
                <div>
                    <div class="order-total-label">Order Total</div>
                    <div class="order-total-val">₹{{ number_format($order->total_amount) }}</div>
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @if($order->status === 'delivered')
                    <button class="btn-reorder" onclick="reorder({{ $order->id }})">
                        <i class="bi bi-arrow-clockwise"></i> Reorder
                    </button>
                    @endif
                    <a href="{{ route('account.orders.show', $order->id) }}" class="btn-view-order">
                        View Details <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

        </div>
        @empty

        <div class="empty-orders" id="emptyState">
            <span class="empty-icon">🛍️</span>
            <h4>No orders yet</h4>
            <p>Looks like you haven't placed any orders.<br>Explore our collection and find something you love!</p>
            <a href="{{ route('products.index') }}"
               style="display:inline-flex;align-items:center;gap:8px;margin-top:20px;padding:12px 28px;border-radius:12px;background:linear-gradient(135deg,#ff4d6d,#e8304d);color:white;font-weight:700;text-decoration:none;font-size:.88rem;box-shadow:0 6px 20px rgba(255,77,109,.3);">
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
    // Update active tab
    document.querySelectorAll('.order-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // Show/hide cards
    const cards = document.querySelectorAll('.order-card');
    let visible  = 0;

    cards.forEach(card => {
        const status = card.dataset.status;
        const show   = filter === 'all' || status === filter;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    // Empty state
    let emptyEl = document.getElementById('emptyState');
    if (!emptyEl && visible === 0) {
        emptyEl = document.createElement('div');
        emptyEl.id = 'emptyState';
        emptyEl.className = 'empty-orders';
        emptyEl.innerHTML = `
            <span class="empty-icon">📦</span>
            <h4>No ${filter === 'all' ? '' : filter} orders</h4>
            <p>No orders found for this status.</p>`;
        document.getElementById('ordersContainer').appendChild(emptyEl);
    } else if (emptyEl) {
        emptyEl.style.display = visible === 0 ? '' : 'none';
    }
}

function reorder(orderId) {
    // Placeholder — implement as needed
    alert('Reorder feature coming soon!');
}
</script>

@endsection
