<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealItem extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
        'deal_id',
        'product_id',
        'description',
        'qty',
        'rate',
        'discount_amount',
        'tax_rate_id',
        'line_total',
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
            'deal_id' => 'integer',
            'product_id' => 'integer',
            'qty' => 'decimal:6',
            'rate' => 'decimal:6',
            'discount_amount' => 'decimal:2',
            'tax_rate_id' => 'integer',
            'line_total' => 'decimal:2',
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
        ];
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class);
    }
}
