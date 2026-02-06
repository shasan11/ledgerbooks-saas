<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\JournalVoucher;
use Illuminate\Database\Eloquent\Factories\Factory;

class JournalvoucheritemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'dr_amount' => fake()->randomFloat(6, 0, 999999999999.999999),
            'cr_amount' => fake()->randomFloat(6, 0, 999999999999.999999),
            'line_note' => fake()->regexify('[A-Za-z0-9]{255}'),
            'created' => fake()->dateTime(),
            'updated' => fake()->dateTime(),
            'account_id' => Account::factory(),
            'journal_voucher_id' => JournalVoucher::factory(),
        ];
    }
}
