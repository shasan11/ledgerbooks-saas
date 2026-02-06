<?php

namespace Database\Factories;

use App\Models\COA;
use App\Models\JournalVoucher;
use Illuminate\Database\Eloquent\Factories\Factory;

class JournalVoucherItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'journal_voucher_id' => JournalVoucher::factory(),
            'account_id' => fake()->word(),
            'dr_amount' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'cr_amount' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'line_note' => fake()->regexify('[A-Za-z0-9]{255}'),
            'c_o_a_id' => COA::factory(),
        ];
    }
}
