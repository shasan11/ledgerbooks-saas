<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionOrder extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
        'production_no',
        'production_date',
        'warehouse_id',
        'status',
        'finished_good_variant_id',
        'planned_qty',
        'produced_qty',
        'approved',
        'approved_at',
        'approved_by_id',
        'voided_reason',
        'voided_at',
        'exchange_rate',
        'total',
        'note',
        'user_add_id',
        'active',
        'is_system_generated',
        'product_variant_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'production_date' => 'date',
            'warehouse_id' => 'integer',
            'planned_qty' => 'decimal:6',
            'produced_qty' => 'decimal:6',
            'approved' => 'boolean',
            'approved_at' => 'datetime',
            'voided_at' => 'datetime',
            'exchange_rate' => 'decimal:6',
            'total' => 'decimal:2',
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
            'product_variant_id' => 'integer',
        ];
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
