{{-- ============================================================
   FOOTER — Compact, SEO-rich, social icons properly centred
   Uses semantic <nav> + <address> for search crawlers.
   All copy is keyword-rich for gifting / hampers / India.
============================================================ --}}
<footer class="footer" itemscope itemtype="https://schema.org/Organization">
    <div class="container">

        {{-- ── Main footer grid ── --}}
        <div class="footer-main">

            {{-- Brand col --}}
            <div class="footer-col footer-col--brand">
                <div class="footer-brand" itemprop="name">Sha<span>nas</span></div>
                <p class="footer-tagline" itemprop="description">
                    India's curated gifting destination — luxury hampers,
                    personalised gifts &amp; fancy home décor, delivered with love.
                </p>

                {{-- Social icons — inline-flex ensures perfect centering --}}
                <div class="footer-social" aria-label="Follow Shanas on social media">
                    <a href="https://instagram.com/shanas" class="social-icon" title="Shanas on Instagram" rel="noopener" target="_blank">
                        <i class="bi bi-instagram" aria-hidden="true"></i>
                    </a>
                    <a href="https://facebook.com/shanas" class="social-icon" title="Shanas on Facebook" rel="noopener" target="_blank">
                        <i class="bi bi-facebook" aria-hidden="true"></i>
                    </a>
                    <a href="https://pinterest.com/shanas" class="social-icon" title="Shanas on Pinterest" rel="noopener" target="_blank">
                        <i class="bi bi-pinterest" aria-hidden="true"></i>
                    </a>
                    <a href="https://wa.me/918001234567" class="social-icon" title="Chat on WhatsApp" rel="noopener" target="_blank">
                        <i class="bi bi-whatsapp" aria-hidden="true"></i>
                    </a>
                    <a href="https://youtube.com/@shanas" class="social-icon" title="Shanas on YouTube" rel="noopener" target="_blank">
                        <i class="bi bi-youtube" aria-hidden="true"></i>
                    </a>
                </div>

                {{-- Mini trust badges --}}
                <div class="footer-trust">
                    <span class="trust-chip">✦ 10k+ Happy Customers</span>
                    <span class="trust-chip">✦ Pan-India Delivery</span>
                </div>
            </div>

            {{-- Shop col --}}
            <div class="footer-col">
                <h5>Shop</h5>
                <nav aria-label="Shop categories">
                    <a href="{{ route('products.index') }}">All Products</a>
                    <a href="{{ route('products.index', ['cat'=>'hampers']) }}">Gift Hampers</a>
                    <a href="{{ route('products.index', ['cat'=>'candles']) }}">Luxury Candles</a>
                    <a href="{{ route('products.index', ['cat'=>'jewellery']) }}">Jewellery</a>
                    <a href="{{ route('products.index', ['cat'=>'decor']) }}">Home Décor</a>
                    <a href="{{ route('products.index', ['cat'=>'corporate']) }}">Corporate Gifts</a>
                </nav>
            </div>

            {{-- Help col --}}
            <div class="footer-col">
                <h5>Help</h5>
                <nav aria-label="Customer support">
                    <a href="#">Track Your Order</a>
                    <a href="#">Shipping Policy</a>
                    <a href="#">Returns &amp; Exchanges</a>
                    <a href="#">Gift Wrapping Guide</a>
                    <a href="{{ route('contact') }}">Contact Us</a>
                    <a href="#">FAQs</a>
                </nav>
            </div>

            {{-- Contact col --}}
            <div class="footer-col">
                <h5>Get in Touch</h5>
                <address class="footer-contact" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                    <a href="tel:+918001234567" class="contact-row" itemprop="telephone">
                        <i class="bi bi-telephone-fill" aria-hidden="true"></i>
                        +91 800 123 4567
                    </a>
                    <a href="mailto:hello@shanas.in" class="contact-row" itemprop="email">
                        <i class="bi bi-envelope-fill" aria-hidden="true"></i>
                        hello@shanas.in
                    </a>
                    <a href="https://wa.me/918001234567" class="contact-row" rel="noopener" target="_blank">
                        <i class="bi bi-whatsapp" aria-hidden="true"></i>
                        WhatsApp Support
                    </a>
                </address>
                <p class="footer-hours">
                    <i class="bi bi-clock" aria-hidden="true"></i>
                    Mon–Sat: 9AM–9PM &nbsp;·&nbsp; Sun: 10AM–6PM
                </p>

                {{-- Compact newsletter micro-form --}}
                <form class="footer-newsletter" id="footerNewsletter" aria-label="Subscribe for offers">
                    <input type="email" name="email" placeholder="Your email…" required
                           aria-label="Email address for newsletter"
                           class="footer-newsletter__input">
                    <button type="submit" class="footer-newsletter__btn" title="Subscribe">
                        <i class="bi bi-send-fill" aria-hidden="true"></i>
                    </button>
                </form>
                <p class="footer-newsletter-note">🎁 15% off your first order on signup</p>
            </div>

        </div>

        {{-- ── Bottom bar ── --}}
        <div class="footer-bottom">
            <span>© {{ date('Y') }} Shanas. All rights reserved.</span>

            <div class="pay-badges" aria-label="Accepted payment methods">
                <span class="pay-badge">UPI</span>
                <span class="pay-badge">VISA</span>
                <span class="pay-badge">MC</span>
                <span class="pay-badge">RuPay</span>
                <span class="pay-badge">EMI</span>
            </div>

            <div class="footer-legal">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Sitemap</a>
            </div>
        </div>

    </div>
</footer>

@push('scripts')
<script>
document.getElementById('footerNewsletter')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const email = this.querySelector('input[name="email"]').value;
    fetch("{{ route('newsletter.subscribe') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ email })
    })
    .then(r => r.json())
    .then(d => { if (d.success) { showToast('🎉 Subscribed! Check your inbox.'); this.reset(); } })
    .catch(() => showToast('Something went wrong. Try again.'));
});
</script>
@endpush