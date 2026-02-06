<?php

namespace Database\Factories;

use App\Models\CashTransfer;
use App\Models\ToAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashtransferitemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'amount' => fake()->randomFloat(6, 0, 999999999999.999999),
            'note' => fake()->regexify('[A-Za-z0-9]{255}'),
            'created' => fake()->dateTime(),
            'updated' => fake()->dateTime(),
            'cash_transfer_id' => CashTransfer::factory(),
            'to_account_id' => ToAccount::factory(),
        ];
    }
}
