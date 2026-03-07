<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{

    public function index()
    {
        $categories = Category::withCount('products')
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

        return view('admin.categories', compact('categories'));
    }

    public function list(): JsonResponse
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'slug'        => 'nullable|string|max:255|unique:categories,slug',
            'parent_id'   => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'icon'        => 'nullable|string|max:100',
            'color'       => 'nullable|string|max:20',
            'is_active'   => 'boolean',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['is_active'] = $data['is_active'] ?? true;

        $category = Category::create($data);

        return response()->json([
            'success'  => true,
            'message'  => 'Category created successfully.',
            'category' => $this->categoryPayload($category),
        ], 201);
    }


    public function show(Category $category): JsonResponse
    {
        return response()->json([
            'success'  => true,
            'category' => $this->categoryPayload($category),
        ]);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug'        => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'parent_id'   => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'icon'        => 'nullable|string|max:100',
            'color'       => 'nullable|string|max:20',
            'is_active'   => 'boolean',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return response()->json([
            'success'  => true,
            'message'  => 'Category updated successfully.',
            'category' => $this->categoryPayload($category->fresh()),
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a category that has products. Reassign the products first.',
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully.',
        ]);
    }
    
    public function toggleActive(Category $category): JsonResponse
    {
        $category->update(['is_active' => ! $category->is_active]);

        return response()->json([
            'success'   => true,
            'is_active' => $category->is_active,
            'message'   => $category->is_active ? 'Category activated.' : 'Category deactivated.',
        ]);
    }

    private function categoryPayload(Category $c): array
    {
        return [
            'id'            => $c->id,
            'name'          => $c->name,
            'slug'          => $c->slug,
            'parent_id'     => $c->parent_id,
            'description'   => $c->description,
            'icon'          => $c->icon,
            'color'         => $c->color,
            'is_active'     => $c->is_active,
            'products_count'=> $c->products()->count(),
        ];
    }
}