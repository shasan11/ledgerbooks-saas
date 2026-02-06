<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountBalance extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
        'account_id',
        'as_of_date',
        'debit_total',
        'credit_total',
        'balance',
        'user_add_id',
        'active',
        'is_system_generated',
        'c_o_a_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'branch_id' => 'integer',
            'as_of_date' => 'date',
            'debit_total' => 'decimal:2',
            'credit_total' => 'decimal:2',
            'balance' => 'decimal:2',
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
            'c_o_a_id' => 'integer',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function cOA(): BelongsTo
    {
        return $this->belongsTo(COA::class);
    }
}
