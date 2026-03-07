<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Order;
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
    
            $attributes = [
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ];
    
            $api->utility->verifyPaymentSignature($attributes);
    
            $amount = session('cartTotal');
            $checkout = session('checkout_data');

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'razorpay_order_id' => $request->razorpay_order_id,
                'status' => 'pending',
                'payment_method' => 'online',
                'total_amount' => $amount,
                'paid_amount' => $amount,
                'payment_status' => 'paid',
                'shipping_address' => $checkout['address'],
                'order_date' => now()
            ]);
    
            // Clear cart
            session()->forget(['cart','cartTotal','razorpay_order_id','checkout_data']);
    
            return redirect()->route('success')->with([
                'order_id'   => $request->razorpay_order_id,
                'payment_id' => $request->razorpay_payment_id,
                'amount'     => $amount,
                'date'       => now(),
            ]);
            
        } catch (\Exception $e) {
            return redirect()->route('failed')
            ->with([
                'order_id' => $request->razorpay_order_id,
                'payment_id' => $request->razorpay_payment_id,
                'amount' => session('cartTotal'),
                'error_message' => $e->getMessage()
            ]);
        }
    }
}
