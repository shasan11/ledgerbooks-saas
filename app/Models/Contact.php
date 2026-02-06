<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
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
        'pan',
        'phone',
        'email',
        'address',
        'accept_purchase',
        'credit_terms_days',
        'credit_limit',
        'notes',
        'branch_id',
        'payable_account_id',
        'receivable_account_id',
        'user_add_id',
        'group_id',
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
            'accept_purchase' => 'boolean',
            'credit_limit' => 'decimal:6',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function payableAccount(): BelongsTo
    {
        return $this->belongsTo(PayableAccount::class);
    }

    public function receivableAccount(): BelongsTo
    {
        return $this->belongsTo(ReceivableAccount::class);
    }

    public function userAdd(): BelongsTo
    {
        return $this->belongsTo(UserAdd::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
