<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VariantAttributeFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->word(),
            'name' => fake()->name(),
            'key' => fake()->regexify('[A-Za-z0-9]{60}'),
            'description' => fake()->text(),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
