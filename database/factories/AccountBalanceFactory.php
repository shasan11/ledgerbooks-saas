<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\COA;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountBalanceFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'account_id' => fake()->word(),
            'as_of_date' => fake()->date(),
            'debit_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'credit_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'balance' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
            'c_o_a_id' => COA::factory(),
        ];
    }
}
