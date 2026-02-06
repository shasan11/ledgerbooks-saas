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
        'created',
        'updated',
        'active',
        'is_system_generated',
        'type',
        'name',
        'code',
        'hs_code',
        'ecommerce_enabled',
        'pos_enabled',
        'description',
        'selling_price',
        'purchase_price',
        'valuation_method',
        'track_inventory',
        'branch_id',
        'purchase_account_id',
        'purchase_return_account_id',
        'sales_account_id',
        'tax_class_id',
        'user_add_id',
        'category_id',
        'primary_unit_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created' => 'timestamp',
            'updated' => 'timestamp',
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
            'ecommerce_enabled' => 'boolean',
            'pos_enabled' => 'boolean',
            'selling_price' => 'decimal:6',
            'purchase_price' => 'decimal:6',
            'track_inventory' => 'boolean',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function purchaseAccount(): BelongsTo
    {
        return $this->belongsTo(PurchaseAccount::class);
    }

    public function purchaseReturnAccount(): BelongsTo
    {
        return $this->belongsTo(PurchaseReturnAccount::class);
    }

    public function salesAccount(): BelongsTo
    {
        return $this->belongsTo(SalesAccount::class);
    }

    public function taxClass(): BelongsTo
    {
        return $this->belongsTo(TaxClass::class);
    }

    public function userAdd(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function primaryUnit(): BelongsTo
    {
        return $this->belongsTo(PrimaryUnit::class);
    }
}
