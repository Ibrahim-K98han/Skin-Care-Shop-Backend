@extends('layouts.shop')

@section('title', 'সব প্রোডাক্ট | ' . config('app.name'))

@section('content')

    <form method="GET" class="bg-white border rounded-lg p-4 mb-6 grid md:grid-cols-4 gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="প্রোডাক্ট খুঁজুন..."
               class="border rounded px-3 py-2 text-sm">

        <select name="category" class="border rounded px-3 py-2 text-sm">
            <option value="">সব ক্যাটাগরি</option>
            @foreach ($categories as $category)
                <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <select name="brand" class="border rounded px-3 py-2 text-sm">
            <option value="">সব ব্র্যান্ড</option>
            @foreach ($brands as $brand)
                <option value="{{ $brand->slug }}" @selected(request('brand') === $brand->slug)>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>

        <button class="bg-pink-600 text-white rounded px-4 py-2 text-sm hover:bg-pink-700">
            খুঁজুন
        </button>
    </form>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @forelse ($products as $product)
            <x-product-card :product="$product" />
        @empty
            <p class="text-gray-500 col-span-full">কোনো প্রোডাক্ট পাওয়া যায়নি।</p>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>

@endsection
