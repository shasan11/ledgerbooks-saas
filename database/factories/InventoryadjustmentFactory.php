<?php

namespace Database\Factories;

use App\Models\ApprovedBy;
use App\Models\Branch;
use App\Models\UserAdd;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryadjustmentFactory extends Factory
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
            'approved' => fake()->boolean(),
            'approved_at' => fake()->dateTime(),
            'voided_reason' => fake()->word(),
            'voided_at' => fake()->dateTime(),
            'exchange_rate' => fake()->randomFloat(6, 0, 999999999999.999999),
            'adjustment_no' => fake()->regexify('[A-Za-z0-9]{50}'),
            'adjustment_date' => fake()->word(),
            'reason' => fake()->regexify('[A-Za-z0-9]{255}'),
            'status' => fake()->regexify('[A-Za-z0-9]{10}'),
            'total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'note' => fake()->word(),
            'approved_by_id' => ApprovedBy::factory(),
            'branch_id' => Branch::factory(),
            'user_add_id' => UserAdd::factory(),
            'warehouse_id' => Warehouse::factory(),
        ];
    }
}
