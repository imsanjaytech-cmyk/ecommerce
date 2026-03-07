<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::with('category')
            ->where('status', 'published');

        if ($request->filled('cat')) {
            $query->whereHas('category', fn($q) =>
                $q->where('slug', $request->cat)
            );
        }

        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(fn($q) =>
                $q->where('name',              'like', "%{$term}%")
                  ->orWhere('short_description','like', "%{$term}%")
                  ->orWhere('tags',             'like', "%{$term}%")
                  ->orWhereHas('category', fn($q) =>
                        $q->where('name', 'like', "%{$term}%")
                  )
            );
        }

        if ($request->filled('occasion')) {
            $occasions = (array) $request->occasion;
            $query->where(fn($q) => collect($occasions)->each(
                fn($occ) => $q->orWhere('tags', 'like', "%{$occ}%")
            ));
        }

        [$min, $max] = $this->parsePriceRange($request->price);
        if ($min !== null) $query->where('regular_price', '>=', $min);
        if ($max !== null) $query->where('regular_price', '<=', $max);

        match ($request->sort) {
            'newest'     => $query->latest(),
            'price_asc'  => $query->orderBy('regular_price'),
            'price_desc' => $query->orderByDesc('regular_price'),
            'rating'     => $query->orderByDesc('total_sales'),
            default      => $query->orderByDesc('is_featured')->orderByDesc('total_sales'),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('pages.products', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::with(['category', 'productImages'])
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Product::with('category')
            ->where('status', 'published')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('pages.product-detail', compact('product', 'related'));
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate(['q' => 'required|string|min:2|max:100']);

        $results = Product::with('category')
            ->where('status', 'published')
            ->where(fn($q) =>
                $q->where('name', 'like', '%'.$request->q.'%')
                  ->orWhere('tags', 'like', '%'.$request->q.'%')
                  ->orWhereHas('category', fn($q) =>
                        $q->where('name', 'like', '%'.$request->q.'%')
                  )
            )
            ->orderByDesc('total_sales')
            ->take(8)
            ->get()
            ->map(fn(Product $p) => [
                'id'       => $p->id,
                'name'     => $p->name,
                'slug'     => $p->slug,
                'price'    => number_format($p->active_price, 2),
                'image'    => $p->thumbnail_url,
                'category' => $p->category?->name ?? '',
            ]);

        return response()->json([
            'results' => $results,
            'total'   => $results->count(),
        ]);
    }
    private function parsePriceRange(?string $range): array
    {
        if (! $range || ! str_contains($range, '-')) {
            return [null, null];
        }

        [$rawMin, $rawMax] = array_pad(explode('-', $range, 2), 2, null);

        $min = is_numeric($rawMin) && (int)$rawMin > 0 ? (int)$rawMin : null;
        $max = is_numeric($rawMax) && (int)$rawMax > 0 ? (int)$rawMax : null;

        return [$min, $max];
    }
}