<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DebitNote extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
        'debit_note_no',
        'debit_note_date',
        'supplier_id',
        'purchase_bill_id',
        'currency_id',
        'reason',
        'status',
        'subtotal',
        'tax_total',
        'grand_total',
        'approved',
        'approved_at',
        'approved_by_id',
        'voided_reason',
        'voided_at',
        'exchange_rate',
        'total',
        'note',
        'user_add_id',
        'active',
        'is_system_generated',
        'contact_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'debit_note_date' => 'date',
            'purchase_bill_id' => 'integer',
            'currency_id' => 'integer',
            'subtotal' => 'decimal:2',
            'tax_total' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'approved' => 'boolean',
            'approved_at' => 'datetime',
            'voided_at' => 'datetime',
            'exchange_rate' => 'decimal:6',
            'total' => 'decimal:2',
            'active' => 'boolean',
            'is_system_generated' => 'boolean',
            'contact_id' => 'integer',
        ];
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function purchaseBill(): BelongsTo
    {
        return $this->belongsTo(PurchaseBill::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
