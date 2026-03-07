@extends('layouts.app')
@section('title','Wishlist')

@section('content')

<div class="container py-5">
    <h3>Your Wishlist</h3>

    <div class="row">
        <div class="col-md-3">
            <div class="card product-card shadow-sm">
                <img src="https://source.unsplash.com/300x300/?romantic,gift" class="card-img-top">
                <div class="card-body text-center">
                    <h6>Romantic Gift</h6>
                    <button class="btn btn-primary btn-sm">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection