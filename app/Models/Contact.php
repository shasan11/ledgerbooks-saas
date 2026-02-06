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
        'branch_id',
        'type',
        'name',
        'code',
        'pan',
        'phone',
        'email',
        'address',
        'group_id',
        'accept_purchase',
        'credit_terms_days',
        'credit_limit',
        'receivable_account_id',
        'payable_account_id',
        'notes',
        'user_add_id',
        'active',
        'is_system_generated',
        'contact_group_id',
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
            'accept_purchase' => 'boolean',
            'credit_limit' => 'decimal:2',
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
            'contact_group_id' => 'integer',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function contactGroup(): BelongsTo
    {
        return $this->belongsTo(ContactGroup::class);
    }
}
