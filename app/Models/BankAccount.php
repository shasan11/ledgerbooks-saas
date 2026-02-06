<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccount extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
        'type',
        'bank_name',
        'display_name',
        'code',
        'account_name',
        'account_number',
        'account_type',
        'currency_id',
        'coa_account_id',
        'description',
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
            'currency_id' => 'integer',
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
            'c_o_a_id' => 'integer',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function cOA(): BelongsTo
    {
        return $this->belongsTo(COA::class);
    }
}
