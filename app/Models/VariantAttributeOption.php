<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariantAttributeOption extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
        'attribute_id',
        'name',
        'key',
        'description',
        'user_add_id',
        'active',
        'is_system_generated',
        'variant_attribute_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
            'variant_attribute_id' => 'integer',
        ];
    }

    public function variantAttribute(): BelongsTo
    {
        return $this->belongsTo(VariantAttribute::class);
    }
}
