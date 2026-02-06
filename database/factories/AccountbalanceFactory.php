<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountbalanceFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'created' => fake()->dateTime(),
            'updated' => fake()->dateTime(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
            'as_of_date' => fake()->word(),
            'debit_total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'credit_total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'balance' => fake()->randomFloat(6, 0, 999999999999.999999),
            'branch_id' => Branch::factory(),
            'user_add_id' => User::factory(),
            'account_id' => Account::factory(),
        ];
    }
}
