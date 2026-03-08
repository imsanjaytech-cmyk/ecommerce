<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total'        => Product::count(),
            'in_stock'     => Product::where('stock_status', 'in_stock')->count(),
            'low_stock'    => Product::where('stock_status', 'low_stock')->count(),
            'out_of_stock' => Product::where('stock_status', 'out_of_stock')->count(),
        ];

        return view('admin.products', compact('categories', 'stats'));
    }

    public function list(Request $request): JsonResponse
    {
        $query = Product::with('category')
            ->when($request->search, fn($q, $s) =>
                $q->where(fn($q) =>
                    $q->where('name',  'like', "%{$s}%")
                      ->orWhere('sku', 'like', "%{$s}%")
                      ->orWhere('brand','like',"%{$s}%")
                )
            )
            ->when($request->status && $request->status !== 'all', fn($q) =>
                $q->where('status', $request->status)
            )
            ->when($request->stock_status && $request->stock_status !== 'all', fn($q) =>
                $q->where('stock_status', $request->stock_status)
            )
            ->when($request->category_id, fn($q) =>
                $q->where('category_id', $request->category_id)
            )
            ->orderBy($request->sort_by ?? 'created_at', $request->sort_dir ?? 'desc');

        $paginator = $query->paginate($request->per_page ?? 10);

        $items = $paginator->getCollection()->map(fn(Product $p) => [
            'id'             => $p->id,
            'name'           => $p->name,
            'sku'            => $p->sku,
            'category'       => $p->category?->name ?? '—',
            'regular_price'  => number_format($p->regular_price, 2),
            'sale_price'     => $p->sale_price ? number_format($p->sale_price, 2) : null,
            'active_price'   => number_format($p->active_price, 2),
            'stock_quantity' => $p->stock_quantity,
            'stock_status'   => $p->stock_status,
            'stock_badge'    => $p->stock_badge,
            'status'         => $p->status,
            'status_badge'   => $p->status_badge,
            'is_featured'    => $p->is_featured,
            'total_sales'    => $p->total_sales,
            'thumbnail_url'  => $p->thumbnail_url,
            'created_at'     => $p->created_at->format('M d, Y'),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $items,
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'short_description'   => 'nullable|string|max:500',
            'description'         => 'nullable|string',
            'sku'                 => 'required|string|max:100|unique:products,sku',
            'barcode'             => 'nullable|string|max:100',
            'regular_price'       => 'required|numeric|min:0',
            'sale_price'          => 'nullable|numeric|min:0',
            'cost_price'          => 'nullable|numeric|min:0',
            'tax_class'           => 'in:standard,reduced,zero',
            'stock_quantity'      => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'manage_stock'        => 'boolean',
            'weight'              => 'nullable|numeric|min:0',
            'length'              => 'nullable|numeric|min:0',
            'width'               => 'nullable|numeric|min:0',
            'height'              => 'nullable|numeric|min:0',
            'category_id'         => 'nullable|exists:categories,id',
            'brand'               => 'nullable|string|max:100',
            'tags'                => 'nullable|string|max:500',
            'status'              => 'in:published,draft,scheduled',
            'is_featured'         => 'boolean',
            'thumbnail'           => 'nullable|image|max:5120',
            'images.*'            => 'nullable|image|max:5120',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('thumbnail')) {

                if (!Storage::disk('public')->exists('products/thumbnails')) {
                    Storage::disk('public')->makeDirectory('products/thumbnails');
                }
            
                $data['thumbnail'] = $request->file('thumbnail')
                    ->store('products/thumbnails', 'public');
            }

            $data['slug'] = Str::slug($data['name']);

            $product = Product::create($data);

            if ($request->hasFile('images')) {

                if (!Storage::disk('public')->exists('products/images')) {
                    Storage::disk('public')->makeDirectory('products/images');
                }

                foreach ($request->file('images') as $i => $file) {
            
                    $path = $file->store('products/images', 'public');
            
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path'       => $path,
                        'sort_order' => $i,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully.',
                'product' => $this->productPayload($product->fresh('category')),
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Product $product): JsonResponse
    {
        $product->load('category', 'productImages');

        return response()->json([
            'success' => true,
            'product' => array_merge($this->productPayload($product), [
                'short_description'   => $product->short_description,
                'description'         => $product->description,
                'barcode'             => $product->barcode,
                'sale_price'          => $product->sale_price,
                'cost_price'          => $product->cost_price,
                'tax_class'           => $product->tax_class,
                'manage_stock'        => $product->manage_stock,
                'low_stock_threshold' => $product->low_stock_threshold,
                'weight'              => $product->weight,
                'length'              => $product->length,
                'width'               => $product->width,
                'height'              => $product->height,
                'brand'               => $product->brand,
                'tags'                => $product->tags,
                'product_images'      => $product->productImages->map(fn($img) => [
                    'id'  => $img->id,
                    'url' => str_starts_with($img->path, 'http')
                                ? $img->path
                                : asset('storage/'.$img->path),
                ]),
            ]),
        ]);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'short_description'   => 'nullable|string|max:500',
            'description'         => 'nullable|string',
            'sku'                 => ['required','string','max:100',
                                      Rule::unique('products','sku')->ignore($product->id)],
            'barcode'             => 'nullable|string|max:100',
            'regular_price'       => 'required|numeric|min:0',
            'sale_price'          => 'nullable|numeric|min:0',
            'cost_price'          => 'nullable|numeric|min:0',
            'tax_class'           => 'in:standard,reduced,zero',
            'stock_quantity'      => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'manage_stock'        => 'boolean',
            'weight'              => 'nullable|numeric|min:0',
            'length'              => 'nullable|numeric|min:0',
            'width'               => 'nullable|numeric|min:0',
            'height'              => 'nullable|numeric|min:0',
            'category_id'         => 'nullable|exists:categories,id',
            'brand'               => 'nullable|string|max:100',
            'tags'                => 'nullable|string|max:500',
            'status'              => 'in:published,draft,scheduled',
            'is_featured'         => 'boolean',
            'thumbnail'           => 'nullable|image|max:5120',
            'images.*'            => 'nullable|image|max:5120',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('thumbnail')) {
                if ($product->thumbnail && !str_starts_with($product->thumbnail, 'http')) {
                    Storage::disk('public')->delete($product->thumbnail);
                }
                $data['thumbnail'] = $request->file('thumbnail')
                    ->store('products/thumbnails', 'public');
            }

            $data['slug'] = Str::slug($data['name']);

            $product->update($data);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $i => $file) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path'       => $file->store('products/images', 'public'),
                        'sort_order' => $product->productImages()->count() + $i,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully.',
                'product' => $this->productPayload($product->fresh('category')),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:products,id']);
        Product::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => true, 'message' => count($request->ids).' products deleted.']);
    }

    public function toggleFeatured(Product $product): JsonResponse
    {
        $product->update(['is_featured' => ! $product->is_featured]);
        return response()->json([
            'success'     => true,
            'is_featured' => $product->is_featured,
            'message'     => $product->is_featured ? 'Marked as featured.' : 'Removed from featured.',
        ]);
    }

    public function deleteImage(ProductImage $image): JsonResponse
    {
        if (! str_starts_with($image->path, 'http')) {
            Storage::disk('public')->delete($image->path);
        }
        $image->delete();
        return response()->json(['success' => true, 'message' => 'Image deleted.']);
    }

    public function stats(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'stats'   => [
                'total'        => Product::count(),
                'in_stock'     => Product::where('stock_status', 'in_stock')->count(),
                'low_stock'    => Product::where('stock_status', 'low_stock')->count(),
                'out_of_stock' => Product::where('stock_status', 'out_of_stock')->count(),
            ],
        ]);
    }

    private function productPayload(Product $p): array
    {
        return [
            'id'             => $p->id,
            'name'           => $p->name,
            'sku'            => $p->sku,
            'category'       => $p->category?->name ?? '—',
            'category_id'    => $p->category_id,
            'regular_price'  => number_format($p->regular_price, 2),
            'active_price'   => number_format($p->active_price, 2),
            'stock_quantity' => $p->stock_quantity,
            'stock_status'   => $p->stock_status,
            'stock_badge'    => $p->stock_badge,
            'status'         => $p->status,
            'status_badge'   => $p->status_badge,
            'is_featured'    => $p->is_featured,
            'total_sales'    => $p->total_sales,
            'thumbnail_url'  => $p->thumbnail_url,
            'created_at'     => $p->created_at->format('M d, Y'),
        ];
    }
}
