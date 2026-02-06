<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealActivity extends Model
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
        'subject',
        'contact_id',
        'deal_id',
        'due_at',
        'completed_at',
        'status',
        'assigned_to_id',
        'description',
        'user_add_id',
        'active',
        'is_system_generated',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'contact_id' => 'integer',
            'deal_id' => 'integer',
            'due_at' => 'datetime',
            'completed_at' => 'datetime',
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
        ];
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }
}
