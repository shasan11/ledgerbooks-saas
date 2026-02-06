<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class POSReturn extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
        'return_no',
        'return_date',
        'pos_order_id',
        'customer_id',
        'reason',
        'status',
        'total',
        'approved',
        'approved_at',
        'approved_by_id',
        'voided_reason',
        'voided_at',
        'exchange_rate',
        'note',
        'user_add_id',
        'active',
        'is_system_generated',
        'p_o_s_order_id',
        'contact_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'return_date' => 'datetime',
            'total' => 'decimal:2',
            'approved' => 'boolean',
            'approved_at' => 'datetime',
            'voided_at' => 'datetime',
            'exchange_rate' => 'decimal:6',
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
            'p_o_s_order_id' => 'integer',
            'contact_id' => 'integer',
        ];
    }

    public function pOSOrder(): BelongsTo
    {
        return $this->belongsTo(POSOrder::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
