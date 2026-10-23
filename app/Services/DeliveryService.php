<?php

namespace App\Services;

use App\Models\Address;

class DeliveryService
{
    public function charge(Address $address, int $subtotal): int
    {
        $threshold = config('shop.free_delivery_threshold');

        if ($threshold !== null && $subtotal >= $threshold) {
            return 0;
        }

        return $address->isInsideDhaka()
            ? config('shop.delivery_charge_dhaka')
            : config('shop.delivery_charge_outside');
    }
}
