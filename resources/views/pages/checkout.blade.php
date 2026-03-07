@extends('layouts.app')
@section('title','Checkout')

@section('content')
<div class="container py-5">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:.82rem">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" style="color:var(--primary)">Home</a>
            </li>
            <li class="breadcrumb-item active">Checkout</li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- ── Left: Form ── --}}
        <div class="col-lg-7">
            <div class="checkout-box">
                <h5 class="fw-700 mb-4" style="font-size:1rem">
                    <i class="bi bi-person me-2" style="color:var(--primary)"></i>
                    Delivery Details
                </h5>

                <form method="POST" action="{{ route('place.order') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.8rem;font-weight:600">Full Name</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="e.g. Anita Sharma"
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.8rem;font-weight:600">Email</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="your@email.com"
                                   value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.8rem;font-weight:600">Phone</label>
                            <input type="tel" name="phone"
                                   class="form-control"
                                   placeholder="+91 98765 43210"
                                   value="{{ old('phone') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.8rem;font-weight:600">City</label>
                            <input type="text" name="city"
                                   class="form-control"
                                   placeholder="Mumbai"
                                   value="{{ old('city') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label" style="font-size:.8rem;font-weight:600">
                                Shipping Address
                            </label>
                            <textarea name="address" rows="3"
                                      class="form-control @error('address') is-invalid @enderror"
                                      placeholder="House / Flat No., Street, Area, Pincode"
                                      required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" style="font-size:.8rem;font-weight:600">
                                Gift Message <span style="color:var(--gray);font-weight:400">(optional)</span>
                            </label>
                            <textarea name="gift_message" rows="2"
                                      class="form-control"
                                      placeholder="Add a personal message to your gift...">{{ old('gift_message') }}</textarea>
                        </div>

                        {{-- Payment method --}}
                        <div class="col-12">
                            <label class="form-label" style="font-size:.8rem;font-weight:600">Payment Method</label>
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                @foreach([
                                    ['cod',   'bi-cash-coin',       'Cash on Delivery'],
                                    ['upi',   'bi-phone',           'UPI / GPay'],
                                    ['card',  'bi-credit-card',     'Card'],
                                ] as [$val, $icon, $label])
                                <label class="pay-option">
                                    <input type="radio" name="payment" value="{{ $val }}"
                                           {{ $val === 'cod' ? 'checked' : '' }}>
                                    <span>
                                        <i class="bi {{ $icon }}"></i> {{ $label }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-4 py-3 fw-600">
                        <i class="bi bi-bag-check me-2"></i> Place Order
                    </button>

                </form>
            </div>
        </div>

        {{-- ── Right: Order summary ── --}}
        <div class="col-lg-5">
            <div class="checkout-box">
                <h5 class="fw-700 mb-4" style="font-size:1rem">
                    <i class="bi bi-bag me-2" style="color:var(--primary)"></i>
                    Order Summary
                </h5>

                @php
                    $cart      = session('cart', []);
                    $cartTotal = session('cartTotal', 0);
                    $shipping  = $cartTotal >= 1500 ? 0 : 99;
                    $grandTotal = $cartTotal + $shipping;
                @endphp

                @forelse($cart as $id => $item)
                <div class="d-flex align-items-center gap-3 mb-3 pb-3"
                     style="border-bottom:1px solid #f0f0f0">
                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                         style="width:54px;height:54px;border-radius:10px;object-fit:cover;flex-shrink:0">
                    <div class="flex-grow-1">
                        <div style="font-size:.85rem;font-weight:600">{{ $item['name'] }}</div>
                        <div style="font-size:.78rem;color:var(--gray)">Qty: {{ $item['qty'] }}</div>
                    </div>
                    <div style="font-size:.9rem;font-weight:700;color:var(--primary)">
                        ₹{{ number_format($item['price'] * $item['qty']) }}
                    </div>
                </div>
                @empty
                    <p class="text-muted text-center py-3" style="font-size:.85rem">
                        Your cart is empty.
                        <a href="{{ route('products.index') }}">Shop now</a>
                    </p>
                @endforelse

                {{-- Totals --}}
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($cartTotal) }}</span>
                </div>
                <div class="total-row">
                    <span>Shipping</span>
                    <span>
                        @if($shipping === 0)
                            <span style="color:var(--success);font-weight:600">FREE</span>
                        @else
                            ₹{{ $shipping }}
                        @endif
                    </span>
                </div>
                @if($shipping > 0)
                <div class="total-row" style="font-size:.75rem;color:var(--gray);border:none;padding-top:0">
                    <span colspan="2">Add ₹{{ number_format(1500 - $cartTotal) }} more for free shipping</span>
                </div>
                @endif
                <div class="total-row total-final mt-2">
                    <span>Total</span>
                    <span>₹{{ number_format($grandTotal) }}</span>
                </div>

                <div class="mt-3 d-flex flex-wrap gap-2" style="font-size:.72rem;color:var(--gray)">
                    <span><i class="bi bi-shield-check" style="color:var(--success)"></i> Secure Checkout</span>
                    <span><i class="bi bi-gift" style="color:var(--primary)"></i> Gift Wrapped</span>
                    <span><i class="bi bi-truck" style="color:var(--primary)"></i> Fast Delivery</span>
                </div>
            </div>
        </div>

    </div>
</div>

@push('styles')
<style>
.pay-option input { display: none; }
.pay-option span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 50px;
    border: 1.5px solid var(--pink-border);
    font-size: .82rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    background: var(--pink-soft);
    color: var(--dark);
}
.pay-option input:checked + span {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}
</style>
@endpush

@endsection