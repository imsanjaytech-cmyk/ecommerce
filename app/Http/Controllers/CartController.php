<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('pages.cart');
    }


    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'nullable|integer|min:1|max:100',
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty     = max(1, (int) $request->input('qty', 1));

        $cart = session()->get('cart', []);
        $id   = $product->id;

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += $qty;
        } else {
            $cart[$id] = [
                'product_id' => $product->id,                
                'name'       => $product->name,
                'sku'        => $product->sku ?? null,                
                'image'      => $product->thumbnail_url ?? asset('images/placeholder.png'),
                'price'      => (float) ($product->sale_price ?: $product->regular_price),
                'category'   => $product->category?->name ?? '',
                'qty'        => $qty,
            ];
        }

        session()->put('cart', $cart);
        $this->recalcTotal($cart);

        if ($request->expectsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => "{$product->name} added to cart.",
                'cart_count' => array_sum(array_column($cart, 'qty')),
            ]);
        }

        return back()->with('success', "{$product->name} added to cart.");
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'change'     => 'required|integer',  
        ]);

        $cart = session()->get('cart', []);
        $id   = $request->product_id;

        if (!isset($cart[$id])) {
            return response()->json(['success' => false, 'message' => 'Item not in cart.'], 404);
        }

        $cart[$id]['qty'] = max(0, $cart[$id]['qty'] + (int) $request->change);

        if ($cart[$id]['qty'] === 0) {
            unset($cart[$id]);
        }

        session()->put('cart', $cart);
        $this->recalcTotal($cart);

        return response()->json([
            'success'    => true,
            'qty'        => $cart[$id]['qty'] ?? 0,
            'subtotal'   => isset($cart[$id]) ? $cart[$id]['price'] * $cart[$id]['qty'] : 0,
            'cart_total' => session('cartTotal', 0),
            'cart_count' => array_sum(array_column($cart, 'qty')),
        ]);
    }

    public function remove(Request $request)
    {
        $id   = $request->product_id;
        $cart = session()->get('cart', []);

        unset($cart[$id]);
        session()->put('cart', $cart);
        $this->recalcTotal($cart);

        return response()->json([
            'success'    => true,
            'cart_total' => session('cartTotal', 0),
            'cart_count' => array_sum(array_column($cart, 'qty')),
        ]);
    }

    public function count()
    {
        $cart = session()->get('cart', []);
        return response()->json([
            'count' => array_sum(array_column($cart, 'qty')),
        ]);
    }

    private function recalcTotal(array $cart): void
    {
        $total = array_reduce($cart, fn($carry, $item) =>
            $carry + ($item['price'] * $item['qty']), 0
        );
        session()->put('cartTotal', $total);
    }
}
