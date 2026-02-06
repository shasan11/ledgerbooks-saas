<?php

namespace Database\Factories;

use App\Models\ApprovedBy;
use App\Models\Branch;
use App\Models\FinishedGoodVariant;
use App\Models\UserAdd;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionorderFactory extends Factory
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
            'production_no' => fake()->regexify('[A-Za-z0-9]{50}'),
            'production_date' => fake()->word(),
            'status' => fake()->regexify('[A-Za-z0-9]{20}'),
            'planned_qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'produced_qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'note' => fake()->word(),
            'approved_by_id' => ApprovedBy::factory(),
            'branch_id' => Branch::factory(),
            'user_add_id' => UserAdd::factory(),
            'finished_good_variant_id' => FinishedGoodVariant::factory(),
            'warehouse_id' => Warehouse::factory(),
        ];
    }
}
