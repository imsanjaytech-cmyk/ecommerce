<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function profile()
    {
        return view('account.profile');
    }

    public function updateProfile(Request $request)
    {
        return back()->with('success', 'Profile updated');
    }

    public function orders()
    {
        $orders = Order::with('items')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        $counts = Order::where('user_id', auth()->id())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $counts = array_merge([
            'pending'    => 0,
            'processing' => 0,
            'shipped'    => 0,
            'delivered'  => 0,
            'cancelled'  => 0,
        ], $counts);

        return view('account.orders', compact('orders', 'counts'));
    }

    public function orderDetail(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load('items.product');

        return view('account.order-detail-customer', compact('order'));
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load('items.product');

        return view('account.order-detail-customer', compact('order'));
    }
}
