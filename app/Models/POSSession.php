<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class POSSession extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
        'shift_id',
        'device_id',
        'started_at',
        'ended_at',
        'user_add_id',
        'active',
        'is_system_generated',
        'p_o_s_shift_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
            'p_o_s_shift_id' => 'integer',
        ];
    }

    public function pOSShift(): BelongsTo
    {
        return $this->belongsTo(POSShift::class);
    }
}
