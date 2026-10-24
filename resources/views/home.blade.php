@extends('layouts.shop')

@section('title', config('app.name') . ' | অরিজিনাল স্কিন কেয়ার')

@section('content')

    <section class="bg-pink-50 rounded-xl p-8 mb-10 text-center">
        <h1 class="text-3xl font-bold text-pink-700">অরিজিনাল স্কিন কেয়ার, সারা বাংলাদেশে</h1>
        <p class="mt-2 text-gray-600">বিশ্বস্ত ব্র্যান্ডের প্রোডাক্ট, ক্যাশ অন ডেলিভারি</p>
        <a href="{{ route('products.index') }}"
           class="inline-block mt-4 bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700">
            কেনাকাটা শুরু করুন
        </a>
    </section>

    @if ($categories->isNotEmpty())
        <section class="mb-10">
            <h2 class="text-xl font-bold mb-4">ক্যাটাগরি</h2>
            <div class="flex flex-wrap gap-3">
                @foreach ($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                       class="bg-white border rounded-full px-4 py-2 text-sm hover:border-pink-600 hover:text-pink-600">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @if ($featured->isNotEmpty())
        <section class="mb-10">
            <h2 class="text-xl font-bold mb-4">ফিচার্ড প্রোডাক্ট</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($featured as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    <section class="mb-10">
        <h2 class="text-xl font-bold mb-4">নতুন প্রোডাক্ট</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @forelse ($latest as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="text-gray-500 col-span-full">এখনো কোনো প্রোডাক্ট নেই।</p>
            @endforelse
        </div>
    </section>

    @if ($brands->isNotEmpty())
        <section>
            <h2 class="text-xl font-bold mb-4">ব্র্যান্ড</h2>
            <div class="flex flex-wrap gap-3">
                @foreach ($brands as $brand)
                    <a href="{{ route('products.index', ['brand' => $brand->slug]) }}"
                       class="bg-white border rounded-lg px-4 py-2 text-sm hover:border-pink-600">
                        {{ $brand->name }}
                    </a>
                @endforeach
            </div>
        </section>
    @endif

@endsection
