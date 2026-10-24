<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{

public const STATUSES = [
    'pending' => 'অপেক্ষমাণ',
    'confirmed' => 'কনফার্মড',
    'processing' => 'প্রসেসিং',
    'shipped' => 'কুরিয়ারে পাঠানো হয়েছে',
    'delivered' => 'ডেলিভারি সম্পন্ন',
    'cancelled' => 'বাতিল',
];

public const PAYMENT_STATUSES = [
    'unpaid' => 'পরিশোধ হয়নি',
    'paid' => 'পরিশোধিত',
];
    protected $fillable = [
        'order_number',
        'user_id',
        'shipping_name',
        'shipping_phone',
        'shipping_division',
        'shipping_district',
        'shipping_area',
        'shipping_address_line',
        'subtotal',
        'delivery_charge',
        'total',
        'payment_method',
        'payment_status',
        'status',
        'note',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}