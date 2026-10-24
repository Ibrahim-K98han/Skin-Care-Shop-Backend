<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'variants' => fn ($q) => $q->where('is_active', true)])
            ->where('is_active', true);

        if ($request->filled('brand')) {
            $query->whereHas('brand', fn ($q) => $q->where('slug', $request->brand));
        }

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();

            if ($category) {
                $ids = $category->children()->pluck('id')->push($category->id);
                $query->whereIn('category_id', $ids);
            }
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        return view('products.index', [
            'products' => $products,
            'categories' => Category::whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->get(),
            'brands' => Brand::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::with(['brand', 'category', 'variants' => fn ($q) => $q->where('is_active', true)])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('products.show', compact('product'));
    }
}
