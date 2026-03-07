@extends('layouts.app')
@section('title','Shop')

@section('content')

<div class="container py-5">
    <h3 class="mb-4">All Products</h3>

    <div class="row">
        @for($i=1;$i<=12;$i++)
        <div class="col-md-3 mb-4">
            <div class="card product-card shadow-sm">
                <img src="https://source.unsplash.com/300x300/?fancy,gift" class="card-img-top">
                <div class="card-body text-center">
                    <h6>Premium Fancy Gift</h6>
                    <p class="price">₹1499</p>
                    <button class="btn btn-primary btn-sm">Add to Cart</button>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>

@endsection