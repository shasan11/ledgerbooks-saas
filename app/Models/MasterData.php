<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasterData extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'name',
        'value',
        'is_boolean',
        'parent_id',
        'user_add_id',
        'active',
        'is_system_generated',
        'master_data_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_boolean' => 'boolean',
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
            'master_data_id' => 'integer',
        ];
    }

    public function masterData(): BelongsTo
    {
        return $this->belongsTo(MasterData::class);
    }
}
