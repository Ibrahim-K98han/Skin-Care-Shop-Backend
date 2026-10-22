<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'ingredients' => $this->ingredients,
            'how_to_use' => $this->how_to_use,
            'thumbnail' => $this->thumbnail ? asset('storage/' . ltrim($this->thumbnail, '/')) : null,
            'is_featured' => $this->is_featured,
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'variants' => ProductVariantResource::collection($this->whenLoaded('variants')),
            'min_price' => $this->whenLoaded('variants', fn () => $this->variants->min(fn ($v) => $v->current_price)),
        ];
    }
}
