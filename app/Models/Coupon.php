<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
        'code',
        'name',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_uses',
        'max_uses_per_customer',
        'valid_from',
        'valid_to',
        'user_add_id',
        'active',
        'is_system_generated',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:6',
            'min_order_amount' => 'decimal:2',
            'valid_from' => 'datetime',
            'valid_to' => 'datetime',
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
        ];
    }
}
