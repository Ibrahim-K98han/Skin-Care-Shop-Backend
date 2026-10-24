<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::with(['brand', 'variants' => fn ($q) => $q->where('is_active', true)])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $latest = Product::with(['brand', 'variants' => fn ($q) => $q->where('is_active', true)])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $brands = Brand::where('is_active', true)->orderBy('name')->get();

        return view('home', compact('featured', 'latest', 'categories', 'brands'));
    }
}
