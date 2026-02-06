<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\COA;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChequeRegisterFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'cheque_no' => fake()->regexify('[A-Za-z0-9]{80}'),
            'coa_account_id' => fake()->numberBetween(1, 1000),
            'bank_account_id' => fake()->numberBetween(1, 1000),
            'contact_id' => fake()->numberBetween(1, 1000),
            'cheque_date' => fake()->date(),
            'received_date' => fake()->date(),
            'amount' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'status' => fake()->randomElement(["issued","received","cleared","bounced","cancelled"]),
            'memo' => fake()->regexify('[A-Za-z0-9]{255}'),
            'approved' => fake()->boolean(),
            'approved_at' => fake()->dateTime(),
            'approved_by_id' => fake()->numberBetween(1, 1000),
            'voided_reason' => fake()->regexify('[A-Za-z0-9]{255}'),
            'voided_at' => fake()->dateTime(),
            'exchange_rate' => fake()->randomFloat(6, 0, 999999999999.999999),
            'total' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'note' => fake()->text(),
            'user_add_id' => fake()->numberBetween(1, 1000),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
            'c_o_a_id' => COA::factory(),
        ];
    }
}
