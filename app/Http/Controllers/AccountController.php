<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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

    public function cancel(Request $request, Order $order): JsonResponse
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorised to cancel this order.',
            ], 403);
        }

        if (! in_array($order->status, ['pending', 'processing'])) {
            $message = match ($order->status) {
                'shipped'   => 'Your order has already been shipped and cannot be cancelled.',
                'delivered' => 'Your order has already been delivered.',
                'cancelled' => 'This order is already cancelled.',
                default     => 'This order cannot be cancelled at this stage.',
            };

            return response()->json(['success' => false, 'message' => $message], 422);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Order #' . $order->order_number . ' has been cancelled.',
        ]);
    }
}
