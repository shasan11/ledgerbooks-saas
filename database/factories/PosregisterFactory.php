<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\CashAccount;
use App\Models\UserAdd;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class PosregisterFactory extends Factory
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
            'code' => fake()->regexify('[A-Za-z0-9]{40}'),
            'name' => fake()->name(),
            'branch_id' => Branch::factory(),
            'cash_account_id' => CashAccount::factory(),
            'user_add_id' => UserAdd::factory(),
            'warehouse_id' => Warehouse::factory(),
        ];
    }
}
