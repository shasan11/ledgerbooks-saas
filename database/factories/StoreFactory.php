<?php

namespace Database\Factories;

use App\Models\;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'name' => fake()->name(),
            'domain' => fake()->regexify('[A-Za-z0-9]{180}'),
            'currency_id' => ::factory(),
            'tax_inclusive' => fake()->boolean(),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
