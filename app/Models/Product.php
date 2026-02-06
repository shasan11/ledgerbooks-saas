<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
        'type',
        'name',
        'code',
        'category_id',
        'tax_class_id',
        'primary_unit_id',
        'hs_code',
        'ecommerce_enabled',
        'pos_enabled',
        'description',
        'selling_price',
        'purchase_price',
        'sales_account_id',
        'purchase_account_id',
        'purchase_return_account_id',
        'valuation_method',
        'track_inventory',
        'user_add_id',
        'active',
        'is_system_generated',
        'product_category_id',
        'unit_of_measurement_id',
        'c_o_a_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'branch_id' => 'integer',
            'tax_class_id' => 'integer',
            'ecommerce_enabled' => 'boolean',
            'pos_enabled' => 'boolean',
            'selling_price' => 'decimal:6',
            'purchase_price' => 'decimal:6',
            'track_inventory' => 'boolean',
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
            'product_category_id' => 'integer',
            'unit_of_measurement_id' => 'integer',
            'c_o_a_id' => 'integer',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function taxClass(): BelongsTo
    {
        return $this->belongsTo(TaxClass::class);
    }

    public function unitOfMeasurement(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class);
    }

    public function cOA(): BelongsTo
    {
        return $this->belongsTo(COA::class);
    }
}
