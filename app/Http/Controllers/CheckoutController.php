<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{

    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('info', 'Your cart is empty. Add some items before checking out.');
        }

        $cartTotal   = session()->get('cartTotal', 0);
        $deliveryFee = $cartTotal >= 1500 ? 0 : 99;
        $grandTotal  = $cartTotal + $deliveryFee;

        return view('pages.checkout', compact('cart', 'cartTotal', 'deliveryFee', 'grandTotal'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'address' => 'required|string|max:1000',
        ]);
    
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
    
        $amount = session('cartTotal') * 100; 
        $order = $api->order->create([
            'receipt' => 'ORD_' . uniqid(),
            'amount' => $amount,
            'currency' => 'INR'
        ]);
    
        Session::put('razorpay_order_id', $order['id']);
        Session::put('checkout_data', $request->only(['name','email','address']));

        return view('pages.payment', [
            'order_id' => $order['id'],
            'amount' => $amount,
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address
        ]);
    }
}
