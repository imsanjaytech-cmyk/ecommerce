@extends('layouts.app')

@section('content')
<h1>My Orders</h1>

@if(!$orders)
    <p>You have no orders yet.</p>
@else
    <ul>
        @foreach($orders as $order)
            <li>
                <a href="{{ route('account.order.detail', $order) }}">
                    Order #{{ $order->id }} — {{ $order->status }}
                </a>
            </li>
        @endforeach
    </ul>
@endif
@endsection