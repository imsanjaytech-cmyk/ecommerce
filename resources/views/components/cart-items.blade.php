@php $cart = session('cart', []); @endphp

@if(count($cart) === 0)
    <div class="text-center py-5">
        <div style="font-size:4rem;opacity:.2">🛍️</div>
        <p class="text-muted mt-3">Your cart is empty</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4 mt-2"
           data-bs-dismiss="offcanvas">Start Shopping</a>
    </div>
@else
    @foreach($cart as $id => $item)
    <div class="cart-item-row" id="cart-row-{{ $id }}">
        <img class="cart-item-img"
             src="{{ $item['image'] }}"
             alt="{{ $item['name'] }}">
        <div class="flex-grow-1">
            <div class="cart-item-name">{{ $item['name'] }}</div>
            <div class="cart-item-price">₹{{ number_format($item['price']) }}</div>
            <div class="qty-control">
                <button class="qty-btn" onclick="updateQty({{ $id }}, -1)">−</button>
                <span class="qty-num" id="qty-{{ $id }}">{{ $item['qty'] }}</span>
                <button class="qty-btn" onclick="updateQty({{ $id }}, 1)">+</button>
            </div>
        </div>
        <button class="btn-close btn-sm ms-2" onclick="removeFromCart({{ $id }})"></button>
    </div>
    @endforeach
@endif