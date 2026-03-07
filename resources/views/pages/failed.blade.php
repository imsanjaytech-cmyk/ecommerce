@extends('layouts.app')

@section('title', 'Payment Failed')

@section('content')

<section class="py-5 text-center">
    <div class="container">
        <div class="card p-5 shadow-sm border-0">

            <div class="mb-4">
                <i class="fa fa-times-circle text-danger" style="font-size: 70px;"></i>
            </div>

            <h2 class="fw-bold mb-3 text-danger">Payment Failed ❌</h2>
            <p class="text-muted mb-4">
                Unfortunately, your payment could not be processed.
                Please try again.
            </p>

            <hr>

            <div class="text-start mx-auto" style="max-width: 400px;">
                <p><strong>Order ID:</strong> {{ session('order_id') ?? 'N/A' }}</p>
                <p><strong>Payment ID:</strong> {{ session('payment_id') ?? 'N/A' }}</p>
                <p><strong>Amount:</strong> ₹{{ session('amount') ?? '0' }}</p>
                <p>
                    <strong>Status:</strong>
                    <span class="text-danger">Failed</span>
                </p>
                <p>
                    <strong>Reason:</strong><br>
                    {{ session('error_message') ?? 'Payment verification failed or was cancelled by user.' }}
                </p>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    Try Again
                </a>

                <a href="{{ route('home') }}" class="btn btn-outline-dark">
                    Back to Home
                </a>
            </div>

        </div>
    </div>
</section>

@endsection