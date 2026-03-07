<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shanas — Luxury Gifts & Fancy Items')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>
    @vite('resources/js/app.js')
    <div class="offer-strip">
        <div class="offer-strip-items">
            <span class="offer-strip-item"><i class="bi bi-truck"></i> Free Delivery Above ₹1,500</span>
            <span class="offer-strip-item"><i class="bi bi-gift"></i> Complimentary Gift Wrapping</span>
            <span class="offer-strip-item"><i class="bi bi-arrow-counterclockwise"></i> 30-Day Easy Returns</span>
            <span class="offer-strip-item"><i class="bi bi-shield-check"></i> 100% Secure Checkout</span>
        </div>
    </div>

    <!-- ============ NAVBAR ============ -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                Sha<span>nas</span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav mx-auto gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            Collections
                        </a>
                        <ul class="dropdown-menu border-0 shadow-lg rounded-3 p-2">
                            <li><a class="dropdown-item rounded-2 py-2" href="{{ route('products.index') }}">All Products</a></li>
                            <li><a class="dropdown-item rounded-2 py-2" href="{{ route('products.index', ['cat'=>'hampers']) }}">Gift Hampers</a></li>
                            <li><a class="dropdown-item rounded-2 py-2" href="{{ route('products.index', ['cat'=>'candles']) }}">Candles</a></li>
                            <li><a class="dropdown-item rounded-2 py-2" href="{{ route('products.index', ['cat'=>'jewellery']) }}">Jewellery</a></li>
                            <li><a class="dropdown-item rounded-2 py-2" href="{{ route('products.index', ['cat'=>'decor']) }}">Home Decor</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index', ['occasion'=>'wedding']) }}">Wedding</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index', ['type'=>'corporate']) }}">Corporate Gifts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <!-- Search -->
                    <button class="nav-icon-btn" data-bs-toggle="modal" data-bs-target="#searchModal"
                        title="Search">
                        <i class="bi bi-search"></i>
                    </button>

                    <!-- Wishlist -->
                    @auth
                    <a href="{{ route('wishlist.index') }}" class="nav-icon-btn" title="Wishlist">
                        <i class="bi bi-heart"></i>
                    </a>
                    @endauth

                    <!-- Account -->
                    @guest
                    <a href="{{ route('login') }}" class="nav-icon-btn" title="Login">
                        <i class="bi bi-person"></i>
                    </a>
                    @else
                    <div class="dropdown">
                        <button class="nav-icon-btn" data-bs-toggle="dropdown" title="Account">
                            <i class="bi bi-person-fill"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 p-2">
                            
                            @if(auth()->user()->role == 'admin')
                            <li><a class="dropdown-item rounded-2 py-2" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                            @else
                            <li><a class="dropdown-item rounded-2 py-2" href="{{ route('account.profile') }}">My Profile</a></li>
                            <li><a class="dropdown-item rounded-2 py-2" href="{{ route('account.orders') }}">My Orders</a></li>
                            @endif
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item rounded-2 py-2 text-danger">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endguest

                    <!-- Cart -->
                    <button class="nav-icon-btn" data-bs-toggle="offcanvas"
                        data-bs-target="#cartOffcanvas" title="Cart">
                        <i class="bi bi-bag"></i>
                        <span class="cart-count" id="cartCount">
                            {{ session('cart') ? count(session('cart')) : 0 }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="footer-brand">Sha<span>nas</span></div>
                    <p class="footer-tagline">
                        Curating moments of joy since 2018. Every gift we send carries warmth, thoughtfulness, and a touch of luxury.
                    </p>
                    <div class="footer-social">
                        <a href="#" class="social-icon" title="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-icon" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-icon" title="Pinterest"><i class="bi bi-pinterest"></i></a>
                        <a href="https://wa.me/918001234567" class="social-icon" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                        <a href="#" class="social-icon" title="YouTube"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>

                <!-- Shop Links -->
                <div class="col-lg-2 col-6">
                    <h5>Shop</h5>
                    <a href="{{ route('products.index') }}">All Products</a>
                    <a href="{{ route('products.index', ['cat'=>'hampers']) }}">Gift Hampers</a>
                    <a href="{{ route('products.index', ['cat'=>'candles']) }}">Candles</a>
                    <a href="{{ route('products.index', ['cat'=>'jewellery']) }}">Jewellery</a>
                    <a href="{{ route('products.index', ['cat'=>'decor']) }}">Home Decor</a>
                    <a href="{{ route('products.index', ['type'=>'corporate']) }}">Corporate Gifts</a>
                </div>

                <!-- Help -->
                <div class="col-lg-2 col-6">
                    <h5>Help</h5>
                    <a href="{{ route('track.order') }}">Track Order</a>
                    <a href="#">Shipping Policy</a>
                    <a href="#">Returns & Exchanges</a>
                    <a href="#">Gift Wrapping</a>
                    <a href="{{ route('contact') }}">Contact Us</a>
                    <a href="#">FAQs</a>
                </div>

                <!-- Contact -->
                <div class="col-lg-4">
                    <h5>Get in Touch</h5>
                    <p class="mb-2" style="font-size:.85rem;">
                        <i class="bi bi-telephone me-2" style="color:var(--secondary)"></i>
                        <a href="tel:+918001234567" style="color:#ccc">+91 800 123 4567</a>
                    </p>
                    <p class="mb-2" style="font-size:.85rem;">
                        <i class="bi bi-envelope me-2" style="color:var(--secondary)"></i>
                        <a href="mailto:hello@Shanas.in" style="color:#ccc">hello@Shanas.in</a>
                    </p>
                    <p class="mb-2" style="font-size:.85rem;">
                        <i class="bi bi-whatsapp me-2" style="color:var(--secondary)"></i>
                        <a href="https://wa.me/918001234567" style="color:#ccc">WhatsApp Support</a>
                    </p>
                    <p style="font-size:.78rem;color:rgba(255,255,255,0.35);margin-top:.75rem;">
                        Mon–Sat: 9AM–9PM &nbsp;|&nbsp; Sun: 10AM–6PM
                    </p>
                </div>
            </div>

            <div class="footer-bottom">
                <span>© {{ date('Y') }} Shanas. All rights reserved.</span>
                <div class="pay-badges">
                    <span class="pay-badge">UPI</span>
                    <span class="pay-badge">VISA</span>
                    <span class="pay-badge">MC</span>
                    <span class="pay-badge">RuPay</span>
                    <span class="pay-badge">EMI</span>
                </div>
                <div class="d-flex gap-3" style="font-size:.72rem">
                    <a href="#" style="color:rgba(255,255,255,0.35)">Privacy Policy</a>
                    <a href="#" style="color:rgba(255,255,255,0.35)">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ============ CART OFFCANVAS ============ -->
    <div class="offcanvas offcanvas-end offcanvas-cart" tabindex="-1" id="cartOffcanvas">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-700">
                <i class="bi bi-bag me-2" style="color:var(--primary)"></i> Your Cart
                <span class="badge rounded-pill ms-1" style="background:var(--primary);font-size:.7rem"
                    id="cartBadge">{{ session('cart') ? count(session('cart')) : 0 }}</span>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" id="cartBody">
            {{-- Cart items rendered by JS / Livewire --}}
            @include('components.cart-items')
        </div>
        <div class="p-3 border-top">
            <div class="d-flex justify-content-between mb-3">
                <span class="fw-500">Subtotal</span>
                <span class="fw-700" style="color:var(--primary);font-size:1.1rem" id="cartTotal">
                    ₹{{ session('cartTotal', 0) }}
                </span>
            </div>
            <a href="{{ route('checkout') }}" class="btn btn-primary w-100 py-3">
                Proceed to Checkout <i class="bi bi-arrow-right ms-1"></i>
            </a>
            <button class="btn btn-outline-secondary w-100 mt-2" data-bs-dismiss="offcanvas">
                Continue Shopping
            </button>
            <p class="text-center mt-2" style="font-size:.75rem;color:var(--gray)">
                Free shipping on orders above ₹1,500
            </p>
        </div>
    </div>

    <!-- ============ SEARCH MODAL ============ -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 p-2">
                <div class="modal-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <i class="bi bi-search fs-4" style="color:var(--primary)"></i>
                        <input type="text" class="form-control border-0 fs-5 shadow-none"
                            placeholder="Search for gifts, candles, jewellery..."
                            id="globalSearchInput" autofocus>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div>
                        <p class="text-muted mb-2" style="font-size:.75rem;letter-spacing:.08em;text-transform:uppercase;font-weight:600">
                            Popular Searches
                        </p>
                        <div class="d-flex gap-2 flex-wrap" id="searchSuggestions">
                            @foreach(['Gift Hampers','Luxury Candles','Crystal Items','Wedding Gifts','Personalised Gifts','Home Decor','Chocolate Box'] as $s)
                            <a href="{{ route('products.index', ['q'=>$s]) }}"
                                class="badge rounded-pill border py-2 px-3 text-dark text-decoration-none"
                                style="background:var(--pink-soft);border-color:var(--pink-border)!important;font-weight:500">
                                {{ $s }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    <div id="searchResults" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ TOAST CONTAINER ============ -->
    <div id="toast-wrap"></div>
    <!-- ============ GO TO TOP ============ -->
    <button class="go-top" id="goTop" title="Back to top">
        <i class="bi bi-arrow-up"></i>
    </button>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- App JS -->
    @stack('scripts')
</body>

</html>