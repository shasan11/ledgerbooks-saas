<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class POSReturnItem extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pos_return_id',
        'pos_order_item_id',
        'product_variant_id',
        'qty',
        'unit_price',
        'tax_rate_id',
        'line_total',
        'p_o_s_return_id',
        'p_o_s_order_item_id',
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
            'tax_rate_id' => 'integer',
            'line_total' => 'decimal:2',
            'p_o_s_return_id' => 'integer',
            'p_o_s_order_item_id' => 'integer',
        ];
    }

    public function pOSReturn(): BelongsTo
    {
        return $this->belongsTo(POSReturn::class);
    }

    public function pOSOrderItem(): BelongsTo
    {
        return $this->belongsTo(POSOrderItem::class);
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
