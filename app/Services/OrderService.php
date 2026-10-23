<?php

namespace App\Services;

use App\Models\Address;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(private DeliveryService $delivery)
    {
    }

    public function placeFromCart(User $user, Address $address, ?string $note = null): Order
    {
        return DB::transaction(function () use ($user, $address, $note) {
            $cartItems = CartItem::where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => ['আপনার কার্ট খালি।'],
                ]);
            }

            // ভ্যারিয়েন্টগুলো লক করে আনা (একসাথে দুজন একই স্টক কিনতে পারবে না)
            $variants = ProductVariant::with('product')
                ->whereIn('id', $cartItems->pluck('product_variant_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $subtotal = 0;
            $lines = [];

            foreach ($cartItems as $cartItem) {
                $variant = $variants[$cartItem->product_variant_id];

                if (! $variant->is_active || ! $variant->product->is_active) {
                    throw ValidationException::withMessages([
                        'cart' => ["{$variant->product->name} এখন পাওয়া যাচ্ছে না।"],
                    ]);
                }

                if ($cartItem->quantity > $variant->stock) {
                    throw ValidationException::withMessages([
                        'cart' => ["{$variant->product->name} স্টকে মাত্র {$variant->stock}টি আছে।"],
                    ]);
                }

                $unitPrice = $variant->current_price;
                $lineTotal = $unitPrice * $cartItem->quantity;
                $subtotal += $lineTotal;

                $lines[] = [
                    'variant' => $variant,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];
            }

            $deliveryCharge = $this->delivery->charge($address, $subtotal);

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $user->id,
                'shipping_name' => $address->name,
                'shipping_phone' => $address->phone,
                'shipping_division' => $address->division,
                'shipping_district' => $address->district,
                'shipping_area' => $address->area,
                'shipping_address_line' => $address->address_line,
                'subtotal' => $subtotal,
                'delivery_charge' => $deliveryCharge,
                'total' => $subtotal + $deliveryCharge,
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
                'status' => 'pending',
                'note' => $note,
            ]);

            foreach ($lines as $line) {
                $variant = $line['variant'];

                $order->items()->create([
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'variant_size' => $variant->size,
                    'sku' => $variant->sku,
                    'unit_price' => $line['unit_price'],
                    'quantity' => $line['quantity'],
                    'line_total' => $line['line_total'],
                ]);

                $variant->decrement('stock', $line['quantity']);
            }

            // কার্ট খালি
            CartItem::where('user_id', $user->id)->delete();

            return $order->load('items');
        });
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-' . now()->format('ymd') . '-' . random_int(1000, 9999);
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
