<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class COA extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
        'name',
        'code',
        'description',
        'parent_id',
        'account_type_id',
        'is_group',
        'is_system',
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
            'account_type_id' => 'integer',
            'is_group' => 'boolean',
            'is_system' => 'boolean',
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

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }
}
