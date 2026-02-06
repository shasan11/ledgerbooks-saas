<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cashtransfer extends Model
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
        'approved',
        'approved_at',
        'voided_reason',
        'voided_at',
        'exchange_rate',
        'transfer_no',
        'transfer_date',
        'reference_no',
        'total',
        'note',
        'approved_by_id',
        'branch_id',
        'from_account_id',
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
            'approved' => 'boolean',
            'approved_at' => 'timestamp',
            'voided_at' => 'timestamp',
            'exchange_rate' => 'decimal:6',
            'total' => 'decimal:6',
        ];
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function fromAccount(): BelongsTo
    {
        return $this->belongsTo(FromAccount::class);
    }

    public function userAdd(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
