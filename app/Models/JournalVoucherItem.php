<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalVoucherItem extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'journal_voucher_id',
        'account_id',
        'dr_amount',
        'cr_amount',
        'line_note',
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
            'journal_voucher_id' => 'integer',
            'dr_amount' => 'decimal:2',
            'cr_amount' => 'decimal:2',
            'c_o_a_id' => 'integer',
        ];
    }

    public function journalVoucher(): BelongsTo
    {
        return $this->belongsTo(JournalVoucher::class);
    }

    public function cOA(): BelongsTo
    {
        return $this->belongsTo(COA::class);
    }
}
