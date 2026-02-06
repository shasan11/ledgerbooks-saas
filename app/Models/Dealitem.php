<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dealitem extends Model
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
        'description',
        'qty',
        'rate',
        'discount_amount',
        'line_total',
        'branch_id',
        'deal_id',
        'product_id',
        'tax_rate_id',
        'user_add_id',
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
            'qty' => 'decimal:6',
            'rate' => 'decimal:6',
            'discount_amount' => 'decimal:6',
            'line_total' => 'decimal:6',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
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

    public function userAdd(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
