@extends('layouts.shop')

@section('title', $product->name . ' | ' . config('app.name'))
@section('description', $product->short_description ?? $product->name)

@section('content')

    <div class="grid md:grid-cols-2 gap-8">

        <div class="bg-white border rounded-lg overflow-hidden aspect-square">
            @if ($product->thumbnail)
                <img src="{{ asset('storage/' . $product->thumbnail) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover">
            @endif
        </div>

        <div>
            <a href="{{ route('products.index', ['brand' => $product->brand->slug]) }}"
               class="text-sm text-gray-500 hover:text-pink-600">
                {{ $product->brand->name }}
            </a>
            <h1 class="text-2xl font-bold mt-1">{{ $product->name }}</h1>

            @if ($product->short_description)
                <p class="mt-2 text-gray-600">{{ $product->short_description }}</p>
            @endif

            <div class="mt-6 space-y-2">
                <h2 class="font-semibold">সাইজ ও দাম</h2>
                @foreach ($product->variants as $variant)
                    <div class="flex items-center justify-between bg-white border rounded px-4 py-3">
                        <span>{{ $variant->size ?? 'স্ট্যান্ডার্ড' }}</span>
                        <span class="text-right">
                            @if ($variant->sale_price)
                                <span class="text-gray-400 line-through text-sm">৳{{ number_format($variant->price) }}</span>
                            @endif
                            <span class="font-semibold text-pink-600">৳{{ number_format($variant->current_price) }}</span>
                            @if ($variant->stock <= 0)
                                <span class="block text-xs text-red-500">স্টকে নেই</span>
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>

            <p class="mt-4 text-sm text-gray-500">কার্টে যোগ করার বাটন পরের ধাপে আসবে।</p>
        </div>
    </div>

    @if ($product->description)
        <section class="mt-10 bg-white border rounded-lg p-6">
            <h2 class="font-bold mb-2">বিবরণ</h2>
            <p class="whitespace-pre-line text-gray-700">{{ $product->description }}</p>
        </section>
    @endif

    @if ($product->ingredients)
        <section class="mt-4 bg-white border rounded-lg p-6">
            <h2 class="font-bold mb-2">ইনগ্রেডিয়েন্ট</h2>
            <p class="whitespace-pre-line text-gray-700 text-sm">{{ $product->ingredients }}</p>
        </section>
    @endif

    @if ($product->how_to_use)
        <section class="mt-4 bg-white border rounded-lg p-6">
            <h2 class="font-bold mb-2">ব্যবহারবিধি</h2>
            <p class="whitespace-pre-line text-gray-700">{{ $product->how_to_use }}</p>
        </section>
    @endif

@endsection
