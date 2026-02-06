<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class POSReceipt extends Model
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
        'receipt_no',
        'printed_at',
        'payload',
        'user_add_id',
        'active',
        'is_system_generated',
        'p_o_s_order_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'printed_at' => 'datetime',
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
            'p_o_s_order_id' => 'integer',
        ];
    }

    public function pOSOrder(): BelongsTo
    {
        return $this->belongsTo(POSOrder::class);
    }
}
