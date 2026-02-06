<?php

namespace Database\Factories;

use App\Models\BankAccount;
use App\Models\CashTransfer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashTransferItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'cash_transfer_id' => CashTransfer::factory(),
            'to_account_id' => fake()->word(),
            'amount' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'note' => fake()->regexify('[A-Za-z0-9]{255}'),
            'bank_account_id' => BankAccount::factory(),
        ];
    }
}
