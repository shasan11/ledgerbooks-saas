<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class POSPayment extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
        'pos_order_id',
        'method_id',
        'amount',
        'reference',
        'status',
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
        'p_o_s_order_id',
        'p_o_s_payment_method_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'approved' => 'boolean',
            'approved_at' => 'datetime',
            'voided_at' => 'datetime',
            'exchange_rate' => 'decimal:6',
            'total' => 'decimal:2',
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
            'p_o_s_order_id' => 'integer',
            'p_o_s_payment_method_id' => 'integer',
        ];
    }

    public function pOSOrder(): BelongsTo
    {
        return $this->belongsTo(POSOrder::class);
    }

    public function pOSPaymentMethod(): BelongsTo
    {
        return $this->belongsTo(POSPaymentMethod::class);
    }
}
