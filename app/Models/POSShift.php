<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class POSShift extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
        'register_id',
        'opened_at',
        'opened_by_id',
        'closed_at',
        'closed_by_id',
        'opening_cash',
        'closing_cash',
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
        'p_o_s_register_id',
        'user_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'opening_cash' => 'decimal:2',
            'closing_cash' => 'decimal:2',
            'approved' => 'boolean',
            'approved_at' => 'datetime',
            'voided_at' => 'datetime',
            'exchange_rate' => 'decimal:6',
            'total' => 'decimal:2',
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
            'p_o_s_register_id' => 'integer',
            'user_id' => 'integer',
        ];
    }

    public function pOSRegister(): BelongsTo
    {
        return $this->belongsTo(POSRegister::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
