@extends('layouts.app')

@section('title', 'Contact Us — Shanas')

@section('content')

<!-- Contact Hero -->
<div style="background:linear-gradient(135deg,var(--primary),var(--secondary));padding:60px 0;text-align:center;color:white">
    <div class="container">
        <span class="d-block mb-2" style="font-size:.75rem;letter-spacing:.15em;text-transform:uppercase;opacity:.8">Get in Touch</span>
        <h1 style="font-weight:700;font-size:2.5rem">We'd Love to Hear from You</h1>
        <p style="opacity:.85;max-width:480px;margin:1rem auto 0;font-size:.95rem">
            Whether it's a bulk order, custom gift, or just a question — we're here for you.
        </p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">

        <!-- Contact Channels -->
        <div class="col-lg-4">
            <h4 class="section-title mb-4">Ways to Reach Us</h4>

            @foreach([
                [
                    'icon' => 'bi-whatsapp',
                    'color' => '#25D366',
                    'title' => 'WhatsApp',
                    'sub' => '+91 9086912720',
                    'hint' => 'Instant replies 9AM–9PM',
                    'link' => 'https://wa.me/919086912720'
                ],
                [
                    'icon' => 'bi-telephone',
                    'color' => 'var(--primary)',
                    'title' => 'Phone',
                    'sub' => '+91 9086912720',
                    'hint' => 'Mon–Sat 9AM–9PM',
                    'link' => 'tel:+919086912720'
                ],
                [
                    'icon' => 'bi-envelope',
                    'color' => '#4285F4',
                    'title' => 'Email',
                    'sub' => 'shanas.signature130@gmail.com',
                    'hint' => 'Reply within 24 hours',
                    'link' => 'mailto:shanas.signature130@gmail.com'
                ],
                [
                    'icon' => 'bi-instagram',
                    'color' => '#E1306C',
                    'title' => 'Instagram DM',
                    'sub' => '@shanas_digitalagency',
                    'hint' => 'Tag us or DM directly',
                    'link' => 'https://instagram.com/shanas_digitalagency'
                ],
                [
                    'icon' => 'bi-shop',
                    'color' => 'var(--secondary)',
                    'title' => 'Visit Our Store',
                    'sub' => 'Tiruchirappali',
                    'hint' => 'Mon–Sat 10AM–7PM',
                    'link' => '#'
                ],
            ] as $ch)
            <a href="{{ $ch['link'] }}" class="d-flex align-items-start gap-3 p-3 mb-3 rounded-3 text-decoration-none"
               style="background:var(--vg-amber);border:1.5px solid var(--pink-border);transition:var(--transition);color:var(--dark);font-weight:bold;"
               onmouseover="this.style.borderColor='var(--primary)';this.style.transform='translateY(-2px)'"
               onmouseout="this.style.borderColor='var(--pink-border)';this.style.transform='none'">
                <div style="width:42px;height:42px;border-radius:50%;background:white;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi {{ $ch['icon'] }}" style="color:{{ $ch['color'] }};font-size:1.1rem"></i>
                </div>
                <div>
                    <div class="fw-600" style="font-size:.9rem">{{ $ch['title'] }}</div>
                    <div style="font-size:.85rem;color:var(--primary)">{{ $ch['sub'] }}</div>
                    <div style="font-size:.75rem;color:var(--gray)">{{ $ch['hint'] }}</div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Contact Form -->
        <div class="col-lg-8">
            <div class="checkout-box">
                <h4 class="section-title mb-4">Send Us a Message</h4>

                @if(session('success'))
                <div class="alert alert-success rounded-3 border-0" style="background:rgba(40,167,69,.1)">
                    <i class="bi bi-check-circle-fill me-2" style="color:var(--success)"></i>
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('enquiry.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.82rem;font-weight:600">Your Name *</label>
                            <input type="text" name="name" class="form-control" required
                                   value="{{ old('name') }}" placeholder="Priya Sharma">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.82rem;font-weight:600">Mobile Number *</label>
                            <input type="tel" name="mobile" class="form-control" required
                                   value="{{ old('mobile') }}" placeholder="+91 98765 43210">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.82rem;font-weight:600">Email Address</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email') }}" placeholder="you@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.82rem;font-weight:600">Enquiry Type</label>
                            <select name="type" class="form-control">
                                <option>General Enquiry</option>
                                <option>Bulk / Corporate Order</option>
                                <option>Custom Gift Box</option>
                                <option>Order Support</option>
                                <option>Returns & Refunds</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="font-size:.82rem;font-weight:600">Your Message *</label>
                            <textarea name="message" rows="5" class="form-control" required
                                      placeholder="Tell us what you're looking for or need help with...">{{ old('message') }}</textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-send me-2"></i> Send Message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
