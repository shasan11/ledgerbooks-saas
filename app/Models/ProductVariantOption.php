<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariantOption extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_variant_id',
        'attribute_id',
        'option_id',
        'variant_attribute_id',
        'variant_attribute_option_id',
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
            'variant_attribute_id' => 'integer',
            'variant_attribute_option_id' => 'integer',
        ];
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function variantAttribute(): BelongsTo
    {
        return $this->belongsTo(VariantAttribute::class);
    }

    public function variantAttributeOption(): BelongsTo
    {
        return $this->belongsTo(VariantAttributeOption::class);
    }
}
