<?php

namespace Database\Factories;

use App\Models\BankAccount;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class POSRegisterFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->word(),
            'code' => fake()->regexify('[A-Za-z0-9]{40}'),
            'name' => fake()->name(),
            'warehouse_id' => Warehouse::factory(),
            'cash_account_id' => fake()->word(),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
            'bank_account_id' => BankAccount::factory(),
        ];
    }
}
