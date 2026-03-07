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
        $qty  = $request->qty ?? 1;
    
        if (isset($cart[$product->id])) {
            $cart[$product->id]['qty'] += $qty;
        } else {
    
            $cart[$product->id] = [
                'id'       => $product->id,
                'slug'     => $product->slug,
                'name'     => $product->name,
                'price'    => $product->sale_price ?? $product->regular_price,
                'image' => asset('storage/' . $product->thumbnail),
                'qty'      => $qty,
            ];
        }
    
        session()->put('cart', $cart);
        $this->recalcTotal($cart);
    
        return response()->json([
            'success'   => true,
            'cartCount' => count($cart),
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
        $id   = $request->product_id;
    
        if (isset($cart[$id])) {
            $cart[$id]['qty'] = $request->qty;
            session()->put('cart', $cart);
            $this->recalcTotal($cart);
        }
    
        return response()->json([
            'success'   => true,
            'cartCount' => count($cart),
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
    
        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
            $this->recalcTotal($cart);
        }
    
        return response()->json([
            'success'   => true,
            'cartCount' => count($cart),
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

    private function recalcTotal(array $cart)
    {
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['qty']);
        session()->put('cartTotal', $total);
    }

    /** Dummy product store — replace with DB */
    private function getDummyProducts(): array
    {
        return [
            1 => ['id'=>1,'slug'=>'luxury-candle-gift-set','name'=>'Luxury Candle Gift Set','category'=>'Candles','price'=>2499,'image'=>'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=200&q=80'],
            2 => ['id'=>2,'slug'=>'crystal-wine-glass-set','name'=>'Crystal Wine Glass Set','category'=>'Crystal','price'=>4799,'image'=>'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=200&q=80'],
            3 => ['id'=>3,'slug'=>'artisan-chocolate-box','name'=>'Artisan Chocolate Box','category'=>'Gourmet','price'=>1299,'image'=>'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=200&q=80'],
            4 => ['id'=>4,'slug'=>'gold-plated-jewellery-set','name'=>'Gold-Plated Jewellery Set','category'=>'Jewellery','price'=>6499,'image'=>'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=200&q=80'],
            5 => ['id'=>5,'slug'=>'luxury-skincare-gift-box','name'=>'Luxury Skincare Gift Box','category'=>'Beauty','price'=>3299,'image'=>'https://images.unsplash.com/photo-1563170351-be82bc888aa4?w=200&q=80'],
            6 => ['id'=>6,'slug'=>'handcrafted-ceramic-vase','name'=>'Handcrafted Ceramic Vase','category'=>'Decor','price'=>2199,'image'=>'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=200&q=80'],
            7 => ['id'=>7,'slug'=>'premium-stationery-set','name'=>'Premium Stationery Set','category'=>'Stationery','price'=>1799,'image'=>'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=200&q=80'],
            8 => ['id'=>8,'slug'=>'silver-photo-frame-set','name'=>'Silver Photo Frame Set','category'=>'Keepsakes','price'=>2799,'image'=>'https://images.unsplash.com/photo-1526045612212-70caf35c14df?w=200&q=80'],
        ];
    }
}