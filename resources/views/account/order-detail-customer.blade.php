@extends('layouts.app')
@section('title', 'Order #' . $order->order_number . ' — Shanas')

@section('content')

<style>
.od-hero {
    background: linear-gradient(135deg, #fff0f3 0%, #ffeef5 50%, #f0f4ff 100%);
    padding: 36px 0 28px;
    position: relative; overflow: hidden;
}
/* Decorative blobs */
.od-hero::before {
    content: ''; position: absolute;
    width: 300px; height: 300px; border-radius: 50%;
    background: radial-gradient(circle, rgba(255,77,109,.08) 0%, transparent 70%);
    top: -80px; right: -60px; pointer-events: none;
}
.od-hero::after {
    content: ''; position: absolute;
    width: 200px; height: 200px; border-radius: 50%;
    background: radial-gradient(circle, rgba(99,102,241,.07) 0%, transparent 70%);
    bottom: -50px; left: 10%; pointer-events: none;
}
.od-hero h1 { font-size: 1.6rem; font-weight: 800; color: #1a1a1a; margin-bottom: 4px; }
.od-hero p  { color: #9199a6; font-size: .85rem; }

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

/* ── Item rows ── */
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

/* ── Cancel section ── */
.cancel-section {
    border: 1.5px solid #fecaca;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 12px;
}
.cancel-section-head {
    background: #fef2f2; padding: 12px 16px;
    border-bottom: 1px solid #fecaca;
    display: flex; align-items: center; gap: 8px;
    font-size: .78rem; font-weight: 700; color: #b91c1c;
}
.cancel-section-body { padding: 14px 16px; background: white; }
.cancel-policy-item {
    display: flex; align-items: flex-start; gap: 8px;
    font-size: .76rem; color: #6c757d; margin-bottom: 8px; line-height: 1.5;
}
.cancel-policy-item i { color: #b91c1c; margin-top: 2px; flex-shrink: 0; }
.cancel-policy-item:last-child { margin-bottom: 0; }
.btn-cancel-full {
    display: flex; align-items: center; justify-content: center; gap: 7px;
    width: 100%; padding: 11px 16px; border-radius: 10px;
    background: white; border: 1.5px solid #fecaca;
    color: #b91c1c; font-size: .82rem; font-weight: 700;
    cursor: pointer; font-family: inherit; transition: all .2s;
    margin-top: 12px;
}
.btn-cancel-full:hover { background: #fef2f2; border-color: #b91c1c; }

.shipped-lock {
    background: #f5f6fb; border-radius: 10px;
    padding: 12px 14px; font-size: .76rem; color: #9199a6;
    display: flex; align-items: center; gap: 8px;
    border: 1px solid #eef0f4;
}
.shipped-lock i { color: #6d28d9; flex-shrink: 0; }

/* ── Cancel Modal ── */
.cancel-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.45); z-index: 9999;
    align-items: center; justify-content: center;
    backdrop-filter: blur(3px);
}
.cancel-overlay.show { display: flex; }
.cancel-modal {
    background: white; border-radius: 18px;
    width: 100%; max-width: 440px; margin: 16px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
    animation: slideUp .25s ease;
}
@keyframes slideUp {
    from { transform: translateY(30px); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
}
.cancel-modal-head {
    background: #fef2f2; padding: 20px 22px 16px;
    border-bottom: 1px solid #fecaca;
    display: flex; align-items: center; gap: 12px;
}
.cancel-modal-head .icon { font-size: 1.6rem; color: #b91c1c; }
.cancel-modal-head h3 { font-size: 1rem; font-weight: 800; color: #1a1a1a; margin: 0 0 2px; }
.cancel-modal-head p  { font-size: .76rem; color: #9199a6; margin: 0; }
.cancel-modal-body { padding: 20px 22px; }
.cancel-modal-body label {
    font-size: .72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .05em; color: #9199a6; display: block; margin-bottom: 8px;
}
.cancel-reason-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 16px; }
.reason-chip {
    padding: 9px 10px; border-radius: 9px; border: 1.5px solid #eef0f4;
    background: white; font-size: .73rem; font-weight: 600; color: #3d3d3d;
    cursor: pointer; text-align: left; font-family: inherit; transition: all .16s;
    display: flex; align-items: center; gap: 6px;
}
.reason-chip:hover { border-color: #ff4d6d; color: #ff4d6d; background: #fff0f3; }
.reason-chip.selected { border-color: #ff4d6d; background: #fff0f3; color: #ff4d6d; }
.reason-chip i { flex-shrink: 0; }
.cancel-note {
    width: 100%; padding: 10px 12px; border: 1.5px solid #eef0f4;
    border-radius: 9px; font-family: inherit; font-size: .8rem; resize: none;
    outline: none; transition: border-color .18s; color: #3d3d3d;
}
.cancel-note:focus { border-color: #ff4d6d; }
.cancel-modal-foot {
    padding: 14px 22px; border-top: 1px solid #f4f5f8;
    display: flex; gap: 10px; justify-content: flex-end;
}
.btn-keep  {
    padding: 9px 18px; border-radius: 9px; border: 1.5px solid #eef0f4;
    background: white; font-size: .8rem; font-weight: 700; color: #6c757d;
    cursor: pointer; font-family: inherit; transition: all .18s;
}
.btn-keep:hover { border-color: #ddd; color: #1a1a1a; }
.btn-confirm-cancel {
    padding: 9px 20px; border-radius: 9px; border: none;
    background: linear-gradient(135deg, #ef4444, #b91c1c);
    color: white; font-size: .8rem; font-weight: 700;
    cursor: pointer; font-family: inherit; transition: all .18s;
    box-shadow: 0 3px 12px rgba(239,68,68,.3);
}
.btn-confirm-cancel:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(239,68,68,.35); }
.btn-confirm-cancel:disabled { opacity: .5; cursor: not-allowed; transform: none; }
.cancel-warning {
    background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px;
    padding: 9px 12px; font-size: .74rem; color: #92400e;
    display: flex; gap: 7px; align-items: flex-start; margin-bottom: 14px;
}
.cancel-warning i { flex-shrink: 0; margin-top: 1px; }

/* ── Promo banners ── */
.promo-banner {
    border-radius: 16px; overflow: hidden;
    text-decoration: none; display: block;
    transition: transform .22s, box-shadow .22s;
    margin-bottom: 12px;
}
.promo-banner:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,.13); }
.promo-banner .pb-inner {
    padding: 18px 18px 16px;
    display: flex; flex-direction: column; gap: 4px;
}
.promo-banner .pb-tag {
    font-size: .58rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase;
    padding: 2px 8px; border-radius: 20px; width: fit-content;
}
.promo-banner .pb-icon { font-size: 1.6rem; margin-bottom: 2px; }
.promo-banner .pb-title { font-size: .9rem; font-weight: 800; line-height: 1.25; }
.promo-banner .pb-sub   { font-size: .72rem; opacity: .78; line-height: 1.4; }
.promo-banner .pb-cta   {
    margin-top: 8px; font-size: .68rem; font-weight: 800;
    padding: 6px 14px; border-radius: 20px; width: fit-content;
}

.pb-pink   { background: linear-gradient(135deg, #ff4d6d, #ff8fab); color: white; }
.pb-pink   .pb-tag { background: rgba(255,255,255,.25); color: white; }
.pb-pink   .pb-cta { background: white; color: #ff4d6d; }

.pb-gold   { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white; }
.pb-gold   .pb-tag { background: rgba(255,255,255,.25); color: white; }
.pb-gold   .pb-cta { background: white; color: #d97706; }

.pb-mint   { background: linear-gradient(135deg, #10b981, #34d399); color: white; }
.pb-mint   .pb-tag { background: rgba(255,255,255,.25); color: white; }
.pb-mint   .pb-cta { background: white; color: #059669; }

.pb-violet { background: linear-gradient(135deg, #7c3aed, #a78bfa); color: white; }
.pb-violet .pb-tag { background: rgba(255,255,255,.25); color: white; }
.pb-violet .pb-cta { background: white; color: #7c3aed; }

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

/* ── Toast ── */
.toast-msg {
    display: none; position: fixed;
    bottom: 28px; left: 50%; transform: translateX(-50%);
    background: #15803d; color: white;
    padding: 11px 22px; border-radius: 10px;
    font-size: .82rem; font-weight: 700;
    box-shadow: 0 6px 20px rgba(0,0,0,.2);
    z-index: 99999; white-space: nowrap;
}

@media (max-width: 576px) {
    .od-hero h1 { font-size: 1.3rem; }
    .od-track-steps::before,
    .od-progress-line { display: none; }
    .od-step-icon { width: 34px; height: 34px; font-size: 12px; }
    .od-item-img, .od-item-img-ph { width: 52px; height: 52px; }
    .cancel-reason-grid { grid-template-columns: 1fr; }
}
@media print {
    .od-back, .od-help, .cancel-section, nav, footer, .promo-banner { display: none !important; }
}
</style>

{{-- Cancel Modal --}}
<div class="cancel-overlay" id="cancelOverlay">
    <div class="cancel-modal">
        <div class="cancel-modal-head">
            <div class="icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <h3>Cancel Order #{{ $order->order_number }}?</h3>
                <p>This action cannot be undone once confirmed.</p>
            </div>
        </div>
        <div class="cancel-modal-body">
            @if($order->payment_status === 'paid')
            <div class="cancel-warning">
                <i class="bi bi-info-circle-fill"></i>
                <span>You were charged <strong>₹{{ number_format($order->total_amount) }}</strong>. A refund will be issued to your original payment method within <strong>5–7 business days</strong>.</span>
            </div>
            @endif

            <label>Why are you cancelling?</label>
            <div class="cancel-reason-grid">
                <button class="reason-chip" data-reason="Changed my mind" onclick="selectReason(this)">
                    <i class="bi bi-emoji-frown"></i> Changed my mind
                </button>
                <button class="reason-chip" data-reason="Wrong item ordered" onclick="selectReason(this)">
                    <i class="bi bi-bag-x"></i> Wrong item ordered
                </button>
                <button class="reason-chip" data-reason="Found a better price" onclick="selectReason(this)">
                    <i class="bi bi-tag"></i> Found better price
                </button>
                <button class="reason-chip" data-reason="Ordered by mistake" onclick="selectReason(this)">
                    <i class="bi bi-arrow-counterclockwise"></i> Ordered by mistake
                </button>
                <button class="reason-chip" data-reason="Delivery too long" onclick="selectReason(this)">
                    <i class="bi bi-clock"></i> Delivery too long
                </button>
                <button class="reason-chip" data-reason="Other" onclick="selectReason(this)">
                    <i class="bi bi-three-dots"></i> Other reason
                </button>
            </div>

            <label style="margin-bottom:6px;">Additional note (optional)</label>
            <textarea class="cancel-note" id="cancelNote" rows="2"
                placeholder="Tell us a bit more (optional)..."></textarea>
        </div>
        <div class="cancel-modal-foot">
            <button class="btn-keep" onclick="closeCancelModal()">
                <i class="bi bi-arrow-left"></i> Keep Order
            </button>
            <button class="btn-confirm-cancel" id="confirmCancelBtn" onclick="submitCancel()" disabled>
                <i class="bi bi-x-circle"></i> Yes, Cancel
            </button>
        </div>
    </div>
</div>

<div class="toast-msg" id="toastMsg"></div>

{{-- Hero --}}
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

        {{-- Tracking --}}
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
                    @php $cls = $i < $cur ? 'done' : ($i === $cur ? 'current' : 'waiting'); @endphp
                    <div class="od-step {{ $cls }}">
                        <div class="od-step-icon">
                            @if($i < $cur) <i class="bi bi-check-lg"></i>
                            @else          <i class="bi {{ $step['icon'] }}"></i>
                            @endif
                        </div>
                        <div class="od-step-label">{{ $step['label'] }}</div>
                    </div>
                    @endforeach
                </div>

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

        {{-- Items --}}
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
                    @if($item->product_image)
                        <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}"
                             class="od-item-img"
                             onerror="this.outerHTML='<div class=\'od-item-img-ph\'><i class=\'bi bi-box-seam\'></i></div>'">
                    @else
                        <div class="od-item-img-ph"><i class="bi bi-box-seam"></i></div>
                    @endif

                    <div style="flex:1;min-width:0;">
                        <div class="od-item-name">{{ $item->product_name }}</div>
                        @if($item->product_sku)
                        <div class="od-item-sku">SKU: {{ $item->product_sku }}</div>
                        @endif
                        <div class="od-item-meta">Qty: {{ $item->quantity }} × ₹{{ number_format($item->unit_price) }}</div>
                    </div>

                    <div style="text-align:right;flex-shrink:0;">
                        <div class="od-item-price">₹{{ number_format($item->subtotal) }}</div>
                    </div>
                </div>
                @endforeach

                @php $delivery = $order->total_amount >= 1500 ? 0 : 99; @endphp
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

        {{-- Shipping Address --}}
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

        {{-- Promotional banners below address --}}
        @if($order->status === 'delivered')
        <a href="{{ route('products.index') }}"
           style="display:flex;align-items:center;gap:16px;padding:18px 20px;background:linear-gradient(135deg,#fff0f3,#ffeef5);border:1.5px solid #ffe4ea;border-radius:16px;text-decoration:none;margin-bottom:20px;transition:transform .2s,box-shadow .2s;"
           onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(255,77,109,.15)'"
           onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div style="font-size:2.2rem;">🔄</div>
            <div>
                <div style="font-size:.9rem;font-weight:800;color:#1a1a1a;margin-bottom:2px;">Love what you got? Order again!</div>
                <div style="font-size:.76rem;color:#9199a6;">Re-explore our collection for your next favourite piece.</div>
            </div>
            <div style="margin-left:auto;font-size:.75rem;font-weight:700;color:#ff4d6d;white-space:nowrap;">Shop Now →</div>
        </a>
        @endif

    </div>

    {{-- ── RIGHT ── --}}
    <div class="col-lg-4">

        {{-- Order Summary --}}
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
                            @if($order->status === 'pending')        <i class="bi bi-hourglass-split"></i>
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

        {{-- Actions --}}
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

        {{-- ── Cancel Section ── --}}
        @php
            $canCancel = in_array($order->status, ['pending', 'processing']);
        @endphp

        @if($canCancel)
        <div class="cancel-section" style="margin-bottom:20px;">
            <div class="cancel-section-head">
                <i class="bi bi-x-circle-fill"></i>
                Cancel This Order
            </div>
            <div class="cancel-section-body">
                <div class="cancel-policy-item">
                    <i class="bi bi-check-circle-fill" style="color:#15803d;"></i>
                    <span>You can cancel <strong>before shipping</strong> starts.</span>
                </div>
                @if($order->payment_status === 'paid')
                <div class="cancel-policy-item">
                    <i class="bi bi-credit-card"></i>
                    <span>Refund of <strong>₹{{ number_format($order->total_amount) }}</strong> will be processed in 5–7 business days.</span>
                </div>
                @else
                <div class="cancel-policy-item">
                    <i class="bi bi-info-circle"></i>
                    <span>No payment was collected. Order will simply be voided.</span>
                </div>
                @endif
                <div class="cancel-policy-item" style="margin-bottom:0;">
                    <i class="bi bi-exclamation-triangle"></i>
                    <span>Cancellation <strong>cannot be undone</strong> once confirmed.</span>
                </div>
                <button class="btn-cancel-full" onclick="openCancelModal()">
                    <i class="bi bi-x-circle"></i> Request Cancellation
                </button>
            </div>
        </div>
        @elseif($order->status === 'shipped')
        <div style="margin-bottom:20px;">
            <div class="shipped-lock">
                <i class="bi bi-lock-fill"></i>
                <div>
                    <div style="font-weight:700;color:#3d3d3d;margin-bottom:2px;">Can't cancel — already shipped</div>
                    <div>Your order is on its way. To return it, contact us after delivery.</div>
                </div>
            </div>
        </div>
        @endif

        {{-- Promo banners --}}
        <a href="{{ route('products.index') }}" class="promo-banner pb-pink">
            <div class="pb-inner">
                <div class="pb-icon">🔥</div>
                <div class="pb-tag">New In</div>
                <div class="pb-title">Fresh Arrivals This Week</div>
                <div class="pb-sub">Don't miss the latest drops — new styles added daily!</div>
                <div class="pb-cta">Shop Now →</div>
            </div>
        </a>

        <a href="{{ route('products.index') }}" class="promo-banner pb-gold">
            <div class="pb-inner">
                <div class="pb-icon">🏷️</div>
                <div class="pb-tag">Sale</div>
                <div class="pb-title">Up to 50% Off</div>
                <div class="pb-sub">Limited time deals on your favourite collections.</div>
                <div class="pb-cta">Grab Deals →</div>
            </div>
        </a>

        <a href="{{ route('products.index') }}" class="promo-banner pb-mint">
            <div class="pb-inner">
                <div class="pb-icon">🚚</div>
                <div class="pb-tag">Free Delivery</div>
                <div class="pb-title">Free Shipping Over ₹1500</div>
                <div class="pb-sub">Stack your cart and save on delivery!</div>
                <div class="pb-cta">Add More Items →</div>
            </div>
        </a>

        {{-- Help --}}
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

<script>
let selectedReason = '';

function openCancelModal() {
    selectedReason = '';
    document.getElementById('cancelNote').value = '';
    document.querySelectorAll('.reason-chip').forEach(c => c.classList.remove('selected'));
    document.getElementById('confirmCancelBtn').disabled = true;
    document.getElementById('cancelOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeCancelModal() {
    document.getElementById('cancelOverlay').classList.remove('show');
    document.body.style.overflow = '';
}

document.getElementById('cancelOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeCancelModal();
});

function selectReason(chip) {
    document.querySelectorAll('.reason-chip').forEach(c => c.classList.remove('selected'));
    chip.classList.add('selected');
    selectedReason = chip.dataset.reason;
    document.getElementById('confirmCancelBtn').disabled = false;
}

async function submitCancel() {
    const btn  = document.getElementById('confirmCancelBtn');
    const note = document.getElementById('cancelNote').value.trim();

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Cancelling…';

    try {
        const resp = await fetch(`/account/orders/{{ $order->id }}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ reason: selectedReason, note })
        });

        const data = await resp.json();

        if (resp.ok && data.success) {
            closeCancelModal();
            showToast('✓ Order has been cancelled successfully.');

            // Reload after 1.5s to reflect new status
            setTimeout(() => window.location.reload(), 1500);
        } else {
            alert(data.message || 'Could not cancel this order. Please try again.');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-x-circle"></i> Yes, Cancel';
        }
    } catch (err) {
        alert('Something went wrong. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-x-circle"></i> Yes, Cancel';
    }
}

function showToast(msg) {
    const t = document.getElementById('toastMsg');
    t.textContent = msg;
    t.style.display = 'block';
}
</script>

@endsection
