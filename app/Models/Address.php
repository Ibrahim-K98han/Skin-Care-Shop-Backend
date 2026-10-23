<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'division',
        'district',
        'area',
        'address_line',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // জেলা ঢাকা কিনা (ডেলিভারি চার্জ ঠিক করতে লাগবে)
    public function isInsideDhaka(): bool
    {
        return strtolower(trim($this->district)) === 'dhaka';
    }
}
