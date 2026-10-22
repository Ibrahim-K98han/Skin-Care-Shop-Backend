<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['brand', 'category', 'variants' => fn ($q) => $q->where('is_active', true)])
            ->where('is_active', true);

        // ব্র্যান্ড ফিল্টার: ?brand=cosrx
        if ($request->filled('brand')) {
            $query->whereHas('brand', fn ($q) => $q->where('slug', $request->brand));
        }

        // ক্যাটাগরি ফিল্টার: ?category=face (সাব-ক্যাটাগরি সহ)
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();

            if ($category) {
                $ids = $category->children()->pluck('id')->push($category->id);
                $query->whereIn('category_id', $ids);
            }
        }

        // সার্চ: ?search=snail
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // ফিচার্ড: ?featured=1
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $products = $query->latest()->paginate(12);

        return ProductResource::collection($products);
    }

    public function show(string $slug)
    {
        $product = Product::with(['brand', 'category', 'variants' => fn ($q) => $q->where('is_active', true)])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return new ProductResource($product);
    }
}
