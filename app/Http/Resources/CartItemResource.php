<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $variant = $this->variant;
        $product = $variant->product;

        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'line_total' => $this->line_total,
            'variant' => new ProductVariantResource($variant),
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'thumbnail' => $product->thumbnail ? asset('storage/' . $product->thumbnail) : null,
                'brand' => $product->brand?->name,
            ],
        ];
    }
}
