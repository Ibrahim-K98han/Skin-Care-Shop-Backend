<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    // কার্টের সব আইটেম
    public function index(Request $request)
    {
        return $this->cartResponse($request);
    }

    // কার্টে যোগ করা
    public function store(Request $request)
    {
        $data = $request->validate([
            'variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $variant = ProductVariant::with('product')->findOrFail($data['variant_id']);

        if (! $variant->is_active || ! $variant->product->is_active) {
            throw ValidationException::withMessages([
                'variant_id' => ['এই প্রোডাক্টটি এখন পাওয়া যাচ্ছে না।'],
            ]);
        }

        $item = CartItem::firstOrNew([
            'user_id' => $request->user()->id,
            'product_variant_id' => $variant->id,
        ]);

        $newQuantity = ($item->exists ? $item->quantity : 0) + ($data['quantity'] ?? 1);

        $this->checkStock($variant, $newQuantity);

        $item->quantity = $newQuantity;
        $item->save();

        return $this->cartResponse($request);
    }

    // পরিমাণ বদলানো
    public function update(Request $request, CartItem $cartItem)
    {
        abort_unless($cartItem->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $this->checkStock($cartItem->variant, $data['quantity']);

        $cartItem->update(['quantity' => $data['quantity']]);

        return $this->cartResponse($request);
    }

    // একটা আইটেম মোছা
    public function destroy(Request $request, CartItem $cartItem)
    {
        abort_unless($cartItem->user_id === $request->user()->id, 403);

        $cartItem->delete();

        return $this->cartResponse($request);
    }

    // পুরো কার্ট খালি করা
    public function clear(Request $request)
    {
        CartItem::where('user_id', $request->user()->id)->delete();

        return $this->cartResponse($request);
    }

    // স্টকের বেশি রাখা যাবে না
    private function checkStock(ProductVariant $variant, int $quantity): void
    {
        if ($quantity > $variant->stock) {
            throw ValidationException::withMessages([
                'quantity' => ["স্টকে মাত্র {$variant->stock}টি আছে।"],
            ]);
        }
    }

    // সব রেসপন্সে একই ফরম্যাটে কার্ট ফেরত
    private function cartResponse(Request $request)
    {
        $items = CartItem::with('variant.product.brand')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'items' => CartItemResource::collection($items)->resolve(),
            'total_quantity' => $items->sum('quantity'),
            'subtotal' => $items->sum(fn ($item) => $item->line_total),
        ]);
    }
}