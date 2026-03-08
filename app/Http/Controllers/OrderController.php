<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function paymentSuccess(Request $request)
    {
        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);

            $cart     = session('cart', []);
            $amount   = session('cartTotal', 0);
            $checkout = session('checkout_data', []);

            $order = Order::create([
                'user_id'          => auth()->id(),
                'order_number'     => 'ORD-' . strtoupper(Str::random(10)),
                'razorpay_order_id'=> $request->razorpay_order_id,
                'status'           => 'pending',
                'payment_method'   => 'online',
                'total_amount'     => $amount,
                'paid_amount'      => $amount,
                'payment_status'   => 'paid',
                'shipping_address' => $checkout['address'] ?? '',
                'order_date'       => now(),
            ]);

            foreach ($cart as $productId => $item) {
                $qty      = (int)   ($item['qty']   ?? 1);
                $price    = (float) ($item['price'] ?? 0);

                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => is_numeric($productId) ? $productId : null,
                    'product_name'  => $item['name']     ?? 'Unknown Product',
                    'product_sku'   => $item['sku']      ?? null,
                    'product_image' => $item['image']    ?? null,
                    'quantity'      => $qty,
                    'unit_price'    => $price,
                    'subtotal'      => round($price * $qty, 2),
                ]);
            }

            session()->forget(['cart', 'cartTotal', 'razorpay_order_id', 'checkout_data']);

            return redirect()->route('success')->with([
                'order_id'   => $request->razorpay_order_id,
                'payment_id' => $request->razorpay_payment_id,
                'amount'     => $amount,
                'date'       => now(),
            ]);

        } catch (\Exception $e) {
            return redirect()->route('failed')->with([
                'order_id'      => $request->razorpay_order_id,
                'payment_id'    => $request->razorpay_payment_id,
                'amount'        => session('cartTotal'),
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
