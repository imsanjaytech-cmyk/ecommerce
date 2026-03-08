<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistIds = session('wishlist', []);

        $products = collect();
        if (!empty($wishlistIds)) {
            $products = Product::whereIn('id', array_keys($wishlistIds))
                ->with('category')
                ->where('status', 'published')
                ->get();
        }

        return view('pages.wishlist', compact('products'));
    }

    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $id       = $request->product_id;
        $wishlist = session('wishlist', []);

        if (isset($wishlist[$id])) {
            unset($wishlist[$id]);
            $added = false;
        } else {
            $wishlist[$id] = true;
            $added = true;
        }

        session()->put('wishlist', $wishlist);

        return response()->json([
            'success' => true,
            'added'   => $added,
            'count'   => count($wishlist),
            'message' => $added ? 'Added to wishlist.' : 'Removed from wishlist.',
        ]);
    }
}
