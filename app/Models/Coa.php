<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coa extends Model
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
        'name',
        'code',
        'description',
        'is_group',
        'is_system',
        'account_type_id',
        'branch_id',
        'parent_id',
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
            'is_group' => 'boolean',
            'is_system' => 'boolean',
        ];
    }

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, "parent_id");
    }

    public function userAdd(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
