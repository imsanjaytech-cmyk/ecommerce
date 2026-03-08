@extends('layouts.app')
@section('title', 'Order #' . $order->order_number . ' — Shanas')

@section('content')

<style>
.od-hero {
    background: linear-gradient(135deg, #fff0f3 0%, #ffeef5 50%, #f0f4ff 100%);
    padding: 36px 0 28px;
}
.od-hero h1 { font-size: 1.6rem; font-weight: 800; color: #1a1a1a; margin-bottom: 4px; }
.od-hero p  { color: #9199a6; font-size: .85rem; }

/* ── Info card shared style ── */
.od-card {
    background: white;
    border-radius: 18px;
    border: 1.5px solid #eef0f4;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,.04);
}
.od-card-header {
    padding: 16px 20px;
    background: #fafbfc;
    border-bottom: 1.5px solid #eef0f4;
    font-size: .82rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #9199a6;
    display: flex;
    align-items: center;
    gap: 8px;
}
.od-card-header i { color: #ff4d6d; font-size: 1rem; }
.od-card-body { padding: 20px; }

/* ── Tracking timeline ── */
.od-track-steps {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    position: relative;
    padding: 10px 0 6px;
}
.od-track-steps::before {
    content: '';
    position: absolute;
    top: 29px;
    left: calc(12.5% + 19px);
    right: calc(12.5% + 19px);
    height: 3px;
    background: #eef0f4;
    z-index: 0;
}
.od-progress-line {
    position: absolute;
    top: 29px;
    left: calc(12.5% + 19px);
    height: 3px;
    background: linear-gradient(90deg, #ff4d6d, #ff8fab);
    z-index: 1;
    transition: width .8s ease;
    border-radius: 2px;
}
.od-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    width: 25%;
    position: relative;
    z-index: 2;
}
.od-step-icon {
    width: 42px; height: 42px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px;
    border: 3px solid #eef0f4;
    background: white;
    transition: all .3s;
    flex-shrink: 0;
}
.od-step.done .od-step-icon {
    background: linear-gradient(135deg, #ff4d6d, #ff8fab);
    border-color: #ff4d6d;
    color: white;
    box-shadow: 0 4px 16px rgba(255,77,109,.35);
}
.od-step.current .od-step-icon {
    border-color: #ff4d6d;
    color: #ff4d6d;
    animation: pulse 1.8s infinite;
}
.od-step.waiting .od-step-icon { color: #d0d5dd; }

@keyframes pulse {
    0%   { box-shadow: 0 0 0 0 rgba(255,77,109,.4); }
    70%  { box-shadow: 0 0 0 10px rgba(255,77,109,0); }
    100% { box-shadow: 0 0 0 0 rgba(255,77,109,0); }
}

.od-step-label {
    font-size: .68rem; font-weight: 700;
    text-align: center; color: #9199a6;
    text-transform: uppercase; letter-spacing: .04em;
    line-height: 1.3;
}
.od-step.done .od-step-label,
.od-step.current .od-step-label { color: #ff4d6d; }

/* ── Item row ── */
.od-item-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid #f4f5f8;
}
.od-item-row:last-child { border-bottom: none; padding-bottom: 0; }
.od-item-img {
    width: 64px; height: 64px;
    border-radius: 12px; object-fit: cover;
    border: 1.5px solid #eef0f4; flex-shrink: 0;
    background: #fafbfc;
}
.od-item-img-ph {
    width: 64px; height: 64px;
    border-radius: 12px; flex-shrink: 0;
    background: #fff0f3; border: 1.5px solid #ffe4ea;
    display: flex; align-items: center; justify-content: center;
    color: #ff8fab; font-size: 22px;
}
.od-item-name  { font-weight: 700; font-size: .9rem; color: #1a1a1a; line-height: 1.3; }
.od-item-sku   { font-size: .72rem; color: #9199a6; font-family: monospace; margin-top: 2px; }
.od-item-meta  { font-size: .78rem; color: #9199a6; margin-top: 3px; }
.od-item-price { font-weight: 800; font-size: .95rem; color: #1a1a1a; white-space: nowrap; }
.od-item-sub   { font-size: .72rem; color: #9199a6; text-align: right; margin-top: 2px; }

/* ── Totals ── */
.od-total-row {
    display: flex; justify-content: space-between;
    padding: 7px 0; font-size: .85rem; color: #6c757d;
}
.od-total-row.grand {
    font-size: 1rem; font-weight: 800; color: #1a1a1a;
    border-top: 1.5px solid #eef0f4;
    padding-top: 12px; margin-top: 4px;
}
.od-total-row.grand span:last-child { color: #ff4d6d; }

/* ── Info rows ── */
.od-info-row {
    display: flex; justify-content: space-between; align-items: flex-start;
    padding: 9px 0; border-bottom: 1px solid #f4f5f8; gap: 12px;
}
.od-info-row:last-child { border-bottom: none; }
.od-info-key {
    font-size: .75rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .05em;
    color: #9199a6; flex-shrink: 0;
}
.od-info-val { font-size: .85rem; font-weight: 600; color: #1a1a1a; text-align: right; }

/* ── Status pills ── */
.spill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 12px; border-radius: 20px;
    font-size: .73rem; font-weight: 700;
}
.spill-pending    { background: #fff5e8; color: #d97706; }
.spill-processing { background: #e8f4ff; color: #1a7cd4; }
.spill-shipped    { background: #f0eeff; color: #7c3aed; }
.spill-delivered  { background: #e8f8ee; color: #1f9c4a; }
.spill-cancelled  { background: #fee8eb; color: #dc3545; }
.spill-paid       { background: #e8f8ee; color: #1f9c4a; }
.spill-unpaid     { background: #fff5e8; color: #d97706; }
.spill-failed     { background: #fee8eb; color: #dc3545; }

/* ── Address ── */
.od-address {
    background: #f5f6fb; border-radius: 12px;
    padding: 14px 16px; font-size: .84rem;
    color: #3d3d3d; line-height: 1.7;
    border: 1px solid #eef0f4;
}

/* ── Back link ── */
.od-back {
    display: inline-flex; align-items: center; gap: 6px;
    color: #9199a6; font-size: .82rem; font-weight: 600;
    text-decoration: none; transition: color .2s;
    margin-bottom: 20px;
}
.od-back:hover { color: #ff4d6d; }

/* ── Help card ── */
.od-help {
    background: linear-gradient(135deg, #fff0f3, #ffeef5);
    border: 1.5px solid #ffe4ea;
    border-radius: 16px;
    padding: 20px;
    text-align: center;
}
.od-help p { font-size: .82rem; color: #9199a6; margin-bottom: 14px; }
.btn-wa {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 20px; border-radius: 10px;
    background: #25d366; color: white;
    font-size: .82rem; font-weight: 700;
    text-decoration: none; transition: all .2s;
    box-shadow: 0 3px 12px rgba(37,211,102,.3);
}
.btn-wa:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(37,211,102,.4); color: white; }

@media (max-width: 576px) {
    .od-hero h1 { font-size: 1.3rem; }
    .od-track-steps::before,
    .od-progress-line { display: none; }
    .od-step-icon { width: 34px; height: 34px; font-size: 12px; }
    .od-item-img, .od-item-img-ph { width: 52px; height: 52px; }
}

@media print {
    .od-back, .od-help, nav, footer { display: none !important; }
}
</style>

{{-- ── Hero ── --}}
<div class="od-hero">
    <div class="container">
        <a href="{{ route('account.orders') }}" class="od-back">
            <i class="bi bi-arrow-left"></i> Back to My Orders
        </a>
        <h1>Order #{{ $order->order_number }}</h1>
        <p>Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
    </div>
</div>

<div class="container py-4">
<div class="row g-4">

    {{-- ── LEFT ── --}}
    <div class="col-lg-8">

        {{-- Tracking ── --}}
        @if($order->status !== 'cancelled')
        @php
            $stepMap  = ['pending' => 0, 'processing' => 1, 'shipped' => 2, 'delivered' => 3];
            $cur      = $stepMap[$order->status] ?? 0;
            $pct      = $cur / 3 * 100;
            $steps    = [
                ['icon' => 'bi-bag-check-fill', 'label' => 'Order Placed'],
                ['icon' => 'bi-gear-fill',       'label' => 'Processing'],
                ['icon' => 'bi-truck',           'label' => 'Shipped'],
                ['icon' => 'bi-house-check-fill','label' => 'Delivered'],
            ];
        @endphp
        <div class="od-card">
            <div class="od-card-header">
                <i class="bi bi-map"></i> Order Tracking
            </div>
            <div class="od-card-body">
                <div class="od-track-steps">
                    <div class="od-progress-line" style="width:{{ $pct }}%;"></div>
                    @foreach($steps as $i => $step)
                    @php
                        $cls = $i < $cur ? 'done' : ($i === $cur ? 'current' : 'waiting');
                    @endphp
                    <div class="od-step {{ $cls }}">
                        <div class="od-step-icon">
                            @if($i < $cur)
                                <i class="bi bi-check-lg"></i>
                            @else
                                <i class="bi {{ $step['icon'] }}"></i>
                            @endif
                        </div>
                        <div class="od-step-label">{{ $step['label'] }}</div>
                    </div>
                    @endforeach
                </div>

                {{-- Current status message ── --}}
                <div style="margin-top:16px;padding:12px 16px;border-radius:10px;background:#fff0f3;border:1px solid #ffe4ea;display:flex;align-items:center;gap:10px;">
                    <i class="bi bi-info-circle-fill" style="color:#ff4d6d;flex-shrink:0;"></i>
                    <span style="font-size:.82rem;color:#3d3d3d;">
                        @if($order->status === 'pending')
                            Your order has been placed and is awaiting confirmation.
                        @elseif($order->status === 'processing')
                            We're preparing your order. It will be packed and shipped soon!
                        @elseif($order->status === 'shipped')
                            Your order is on its way! Expected delivery in 2–5 business days.
                        @elseif($order->status === 'delivered')
                            Your order has been delivered. Thank you for shopping with Shanas! 🎉
                        @endif
                    </span>
                </div>
            </div>
        </div>
        @else
        <div class="od-card">
            <div class="od-card-header"><i class="bi bi-x-circle"></i> Order Status</div>
            <div class="od-card-body">
                <div style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:#fff5f5;border-radius:10px;border:1px solid #fee8eb;">
                    <i class="bi bi-x-circle-fill" style="color:#dc3545;font-size:1.4rem;flex-shrink:0;"></i>
                    <div>
                        <div style="font-weight:700;color:#dc3545;font-size:.9rem;">Order Cancelled</div>
                        <div style="font-size:.78rem;color:#9199a6;margin-top:2px;">This order was cancelled. If you were charged, a refund will be processed within 5–7 business days.</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Items ── --}}
        <div class="od-card">
            <div class="od-card-header">
                <i class="bi bi-bag"></i>
                Order Items
                <span style="margin-left:auto;font-size:.75rem;font-weight:600;color:#9199a6;text-transform:none;letter-spacing:0;">
                    {{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}
                </span>
            </div>
            <div class="od-card-body" style="padding-bottom:8px;">

                @foreach($order->items as $item)
                <div class="od-item-row">
                    {{-- Thumb --}}
                    @if($item->product_image)
                        <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}"
                             class="od-item-img"
                             onerror="this.outerHTML='<div class=\'od-item-img-ph\'><i class=\'bi bi-box-seam\'></i></div>'">
                    @else
                        <div class="od-item-img-ph"><i class="bi bi-box-seam"></i></div>
                    @endif

                    {{-- Info --}}
                    <div style="flex:1;min-width:0;">
                        <div class="od-item-name">{{ $item->product_name }}</div>
                        @if($item->product_sku)
                        <div class="od-item-sku">SKU: {{ $item->product_sku }}</div>
                        @endif
                        <div class="od-item-meta">Qty: {{ $item->quantity }} × ₹{{ number_format($item->unit_price) }}</div>
                    </div>

                    {{-- Price --}}
                    <div style="text-align:right;flex-shrink:0;">
                        <div class="od-item-price">₹{{ number_format($item->subtotal) }}</div>
                    </div>
                </div>
                @endforeach

                {{-- Totals ── --}}
                @php
                    $delivery = $order->total_amount >= 1500 ? 0 : 99;
                @endphp
                <div style="margin-top:16px;padding-top:14px;border-top:1.5px solid #eef0f4;">
                    <div class="od-total-row">
                        <span>Subtotal</span>
                        <span>₹{{ number_format($order->items->sum('subtotal')) }}</span>
                    </div>
                    <div class="od-total-row">
                        <span>Delivery</span>
                        <span>{{ $delivery === 0 ? 'FREE' : '₹' . $delivery }}</span>
                    </div>
                    <div class="od-total-row">
                        <span>Gift Wrapping</span>
                        <span style="color:#1f9c4a;">FREE</span>
                    </div>
                    <div class="od-total-row grand">
                        <span>Total Paid</span>
                        <span>₹{{ number_format($order->total_amount) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Shipping Address ── --}}
        @if($order->shipping_address)
        <div class="od-card">
            <div class="od-card-header"><i class="bi bi-geo-alt-fill"></i> Shipping Address</div>
            <div class="od-card-body">
                <div class="od-address">
                    @if(is_array($order->shipping_address))
                        @foreach($order->shipping_address as $key => $val)
                            @if($val)
                            <div><span style="color:#9199a6;font-size:.75rem;text-transform:capitalize;">{{ $key }}:</span> {{ $val }}</div>
                            @endif
                        @endforeach
                    @else
                        {{ $order->shipping_address }}
                    @endif
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- ── RIGHT ── --}}
    <div class="col-lg-4">

        {{-- Order Summary ── --}}
        <div class="od-card">
            <div class="od-card-header"><i class="bi bi-receipt"></i> Order Summary</div>
            <div class="od-card-body">
                <div class="od-info-row">
                    <span class="od-info-key">Order #</span>
                    <span class="od-info-val" style="color:#ff4d6d;">#{{ $order->order_number }}</span>
                </div>
                <div class="od-info-row">
                    <span class="od-info-key">Date</span>
                    <span class="od-info-val">{{ $order->created_at->format('d M Y') }}</span>
                </div>
                <div class="od-info-row">
                    <span class="od-info-key">Status</span>
                    <span>
                        <span class="spill spill-{{ $order->status }}">
                            @if($order->status === 'pending')    <i class="bi bi-hourglass-split"></i>
                            @elseif($order->status === 'processing') <i class="bi bi-gear-fill"></i>
                            @elseif($order->status === 'shipped')    <i class="bi bi-truck"></i>
                            @elseif($order->status === 'delivered')  <i class="bi bi-check-circle-fill"></i>
                            @elseif($order->status === 'cancelled')  <i class="bi bi-x-circle-fill"></i>
                            @endif
                            {{ ucfirst($order->status) }}
                        </span>
                    </span>
                </div>
                <div class="od-info-row">
                    <span class="od-info-key">Payment</span>
                    <span class="spill spill-{{ $order->payment_status === 'paid' ? 'paid' : ($order->payment_status === 'failed' ? 'failed' : 'unpaid') }}">
                        <i class="bi {{ $order->payment_status === 'paid' ? 'bi-shield-check' : 'bi-clock' }}"></i>
                        {{ ucfirst($order->payment_status ?? 'pending') }}
                    </span>
                </div>
                <div class="od-info-row">
                    <span class="od-info-key">Method</span>
                    <span class="od-info-val">{{ ucfirst($order->payment_method ?? '—') }}</span>
                </div>
                @if($order->razorpay_order_id)
                <div class="od-info-row">
                    <span class="od-info-key">Razorpay ID</span>
                    <span class="od-info-val" style="font-family:monospace;font-size:.75rem;word-break:break-all;">{{ $order->razorpay_order_id }}</span>
                </div>
                @endif
                <div class="od-info-row">
                    <span class="od-info-key">Amount Paid</span>
                    <span class="od-info-val" style="color:#1f9c4a;font-size:.95rem;">₹{{ number_format($order->paid_amount ?? $order->total_amount) }}</span>
                </div>
            </div>
        </div>

        {{-- Actions ── --}}
        <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px;">
            <a href="{{ route('account.orders') }}"
               style="display:flex;align-items:center;justify-content:center;gap:7px;padding:11px 20px;border-radius:12px;background:linear-gradient(135deg,#ff4d6d,#e8304d);color:white;font-weight:700;font-size:.85rem;text-decoration:none;box-shadow:0 4px 16px rgba(255,77,109,.3);transition:all .2s;"
               onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform=''">
                <i class="bi bi-arrow-left"></i> Back to Orders
            </a>
            <button onclick="window.print()"
               style="display:flex;align-items:center;justify-content:center;gap:7px;padding:11px 20px;border-radius:12px;background:white;color:#6c757d;font-weight:600;font-size:.85rem;border:1.5px solid #eef0f4;cursor:pointer;font-family:inherit;transition:all .2s;"
               onmouseover="this.style.borderColor='#ff8fab';this.style.color='#ff4d6d'" onmouseout="this.style.borderColor='#eef0f4';this.style.color='#6c757d'">
                <i class="bi bi-printer"></i> Print Invoice
            </button>
        </div>

        {{-- Need Help ── --}}
        <div class="od-help">
            <div style="font-size:.88rem;font-weight:700;color:#1a1a1a;margin-bottom:6px;">Need Help?</div>
            <p>Have a question about your order? Reach out to us.</p>
            <a href="https://wa.me/918001234567?text=Hi!%20I%20need%20help%20with%20order%20%23{{ $order->order_number }}"
               class="btn-wa" target="_blank">
                <i class="bi bi-whatsapp"></i> WhatsApp Us
            </a>
        </div>

    </div>
</div>
</div>

@endsection