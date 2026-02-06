<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'code' => fake()->regexify('[A-Za-z0-9]{50}'),
            'name' => fake()->name(),
            'address' => fake()->text(),
            'contact_phone' => fake()->regexify('[A-Za-z0-9]{60}'),
            'is_default' => fake()->boolean(),
            'user_add_id' => fake()->numberBetween(1, 1000),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
