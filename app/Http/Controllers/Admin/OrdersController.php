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
        try {
            $status  = $request->input('status', 'all');
            $search  = $request->input('search', '');
            $perPage = (int) $request->input('per_page', 15);
            $perPage = in_array($perPage, [15, 25, 50]) ? $perPage : 15;
    
            $query = Order::with('user')
                ->withCount('items')
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
                ->latest();
    
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
    
            if ($request->expectsJson()) {
                $orders = $query->paginate($perPage)->withQueryString();
                $orders->getCollection()->transform(fn($o) => $this->orderPayload($o));
    
                return response()->json([
                    'success'       => true,
                    'orders'        => $orders,
                    'counts'        => $counts,
                    'total_revenue' => $totalRevenue,
                ]);
            }
    
            $orders = $query->paginate($perPage)->withQueryString();
    
            return view('admin.orders', compact('orders', 'counts', 'status', 'search', 'totalRevenue'));
    
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
            throw $e;
        }
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
        $number = $order->order_number;
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => "Order #{$number} deleted successfully.",
        ]);
    }

    private function orderPayload(Order $o): array
    {
        return [
            'id'                   => $o->id,
            'order_number'         => $o->order_number,
            'customer_name'        => $o->user?->name ?? 'Guest',
            'customer_email'       => $o->user?->email ?? '',
            'items_count'          => $o->items_count ?? 0,
            'total_amount'         => $o->total_amount,
            'paid_amount'          => $o->paid_amount,
            'payment_method'       => $o->payment_method,
            'payment_status'       => $o->payment_status,
            'status'               => $o->status,
            'created_at_formatted' => $o->created_at->format('M d, Y'),
        ];
    }
}

