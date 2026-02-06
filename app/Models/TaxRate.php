<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxRate extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tax_class_id',
        'name',
        'rate_percent',
        'inclusive',
        'active_from',
        'active_to',
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
            'tax_class_id' => 'integer',
            'rate_percent' => 'decimal:4',
            'inclusive' => 'boolean',
            'active_from' => 'date',
            'active_to' => 'date',
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
        ];
    }

    public function taxClass(): BelongsTo
    {
        return $this->belongsTo(TaxClass::class);
    }
}
