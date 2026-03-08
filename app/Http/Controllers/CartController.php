<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        return view('pages.cart');
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer'
        ]);

        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $cart = session()->get('cart', []);
        $id   = (string) $product->id;
        $qty  = max(1, (int) ($request->qty ?? 1));

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += $qty;
        } else {
            $cart[$id] = [
                'id'    => $product->id,
                'slug'  => $product->slug,
                'name'  => $product->name,
                'price' => $product->sale_price ?? $product->regular_price,
                'image' => asset('storage/' . $product->thumbnail),
                'qty'   => $qty,
            ];
        }

        session()->put('cart', $cart);
        $this->recalcTotal($cart);

        return response()->json([
            'success'   => true,
            'message'   => "{$product->name} added to cart.",
            'cartCount' => array_sum(array_column($cart, 'qty')),
            'cartTotal' => number_format(session('cartTotal')),
            'cartHtml'  => view('components.cart-items')->render(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'qty'        => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $id   = (string) $request->product_id;

        if (!isset($cart[$id])) {
            return response()->json(['success' => false, 'message' => 'Item not in cart.'], 404);
        }

        $cart[$id]['qty'] = (int) $request->qty;
        session()->put('cart', $cart);
        $this->recalcTotal($cart);

        return response()->json([
            'success'   => true,
            'cartCount' => array_sum(array_column($cart, 'qty')),
            'cartTotal' => number_format(session('cartTotal')),
            'cartHtml'  => view('components.cart-items')->render(),
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer'
        ]);

        $cart = session()->get('cart', []);
        $id   = (string) $request->product_id;

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            $this->recalcTotal($cart);
        }

        return response()->json([
            'success'   => true,
            'cartCount' => array_sum(array_column($cart, 'qty')),
            'cartTotal' => number_format(session('cartTotal')),
            'cartHtml'  => view('components.cart-items')->render(),
        ]);
    }

    public function clear()
    {
        session()->forget('cart');
        session()->forget('cartTotal');

        return response()->json([
            'success'   => true,
            'cartCount' => 0,
            'cartTotal' => 0,
            'cartHtml'  => view('components.cart-items')->render(),
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
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['qty']);
        session()->put('cartTotal', $total);
    }
}
