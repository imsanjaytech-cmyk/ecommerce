<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $search = $request->input('search', '');

        $orders = Order::with('user')
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->when($search, fn($q) =>
                $q->where(fn($inner) =>
                    $inner->where('order_number', 'like', "%{$search}%")
                          ->orWhereHas('user', fn($u) =>
                              $u->where('name',  'like', "%{$search}%")
                                ->orWhere('email','like', "%{$search}%")
                          )
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = Order::selectRaw("status, count(*) as total")
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

        $totalRevenue = Order::where('status', 'delivered')->sum('total_amount');

        return view('admin.orders', compact('orders', 'counts', 'status', 'search', 'totalRevenue'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product');

        return view('admin.order-detail', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => "Order #{$order->order_number} marked as {$request->status}.",
            'status'  => $order->status,
        ]);
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => "Order #{$order->order_number} deleted.",
        ]);
    }
}