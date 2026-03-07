<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        return view('pages.wishlist');
    }

    public function toggle(Request $request)
    {
        return response()->json([
            'success' => true,
            'added' => true
        ]);
    }
}