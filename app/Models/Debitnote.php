<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Debitnote extends Model
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
        'debit_note_no',
        'debit_note_date',
        'reason',
        'status',
        'subtotal',
        'tax_total',
        'grand_total',
        'total',
        'note',
        'approved_by_id',
        'branch_id',
        'currency_id',
        'supplier_id',
        'user_add_id',
        'purchase_bill_id',
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
            'subtotal' => 'decimal:6',
            'tax_total' => 'decimal:6',
            'grand_total' => 'decimal:6',
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

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function userAdd(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseBill(): BelongsTo
    {
        return $this->belongsTo(PurchaseBill::class);
    }
}
