<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('category')
            ->where('status', 'published')
            ->where('is_featured', true)
            ->orderByDesc('total_sales')
            ->take(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->withCount(['products' => fn($q) =>
                $q->where('status', 'published')
            ])
            ->orderBy('name')
            ->get();

        $newArrivals = Product::with('category')
            ->where('status', 'published')
            ->latest()
            ->take(4)
            ->get();

        $topSelling = Product::with('category')
            ->where('status', 'published')
            ->orderByDesc('total_sales')
            ->take(4)
            ->get();

        return view('pages.home', compact(
            'featuredProducts',
            'categories',
            'newArrivals',
            'topSelling'
        ));
    }

    public function newsletterSubscribe(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);

        return response()->json(['success' => true, 'message' => 'Subscribed!']);
    }
}