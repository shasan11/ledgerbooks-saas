<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class POSOrderItem extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pos_order_id',
        'product_variant_id',
        'product_name',
        'qty',
        'unit_price',
        'discount_amount',
        'tax_rate_id',
        'line_total',
        'p_o_s_order_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'product_variant_id' => 'integer',
            'qty' => 'decimal:6',
            'unit_price' => 'decimal:6',
            'discount_amount' => 'decimal:2',
            'tax_rate_id' => 'integer',
            'line_total' => 'decimal:2',
            'p_o_s_order_id' => 'integer',
        ];
    }

    public function pOSOrder(): BelongsTo
    {
        return $this->belongsTo(POSOrder::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class);
    }
}
