@extends('layouts.app')

@section('title', 'Order Success')

@section('content')

<section class="py-5 text-center">
    <div class="container">
        <div class="card p-5 shadow-sm border-0">

            <div class="mb-4">
                <i class="fa fa-check-circle text-success" style="font-size: 70px;"></i>
            </div>

            <h2 class="fw-bold mb-3">Payment Successful 🎉</h2>
            <p class="text-muted mb-4">
                Your payment has been verified successfully.
            </p>

            <hr>

            <div class="text-start mx-auto" style="max-width: 400px;">
                <p><strong>Order ID:</strong> {{ session('order_id') }}</p>
                <p><strong>Payment ID:</strong>{{ session('payment_id') }} </p>
                <p><strong>Amount Paid:</strong> ₹{{ session('amount') }}
                </p>
                <p><strong>Date:</strong> {{ session('date') }}</p>
                <p><strong>Status:</strong> <span class="text-success">Paid</span></p>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    Continue Shopping
                </a>

                <a href="{{ route('home') }}" class="btn btn-outline-dark">
                    Back to Home
                </a>
            </div>

        </div>
    </div>
</section>

@endsection