@props(['product'])

@php
    $minPrice = $product->variants->min(fn ($v) => $v->current_price);
@endphp

<a href="{{ route('products.show', $product->slug) }}"
   class="block bg-white rounded-lg border hover:shadow-md transition overflow-hidden">
    <div class="aspect-square bg-gray-100">
        @if ($product->thumbnail)
            <img src="{{ asset('storage/' . $product->thumbnail) }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover">
        @endif
    </div>
    <div class="p-3">
        <p class="text-xs text-gray-500">{{ $product->brand->name }}</p>
        <h3 class="text-sm font-medium line-clamp-2">{{ $product->name }}</h3>
        @if ($minPrice)
            <p class="mt-1 font-semibold text-pink-600">৳{{ number_format($minPrice) }}</p>
        @else
            <p class="mt-1 text-sm text-gray-400">স্টকে নেই</p>
        @endif
    </div>
</a>
