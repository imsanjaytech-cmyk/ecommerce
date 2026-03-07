<?php

namespace App\Http\Controllers;

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

    public function orders() {
        $orders = auth()->user()->orders; 
        return view('account.orders', compact('orders'));
    }

    public function orderDetail($order)
    {
        return view('account.order-detail');
    }
}