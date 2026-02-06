<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class POSDiscountProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->numberBetween(1, 1000),
            'name' => fake()->name(),
            'discount_type' => fake()->randomElement(["percent","fixed"]),
            'value' => fake()->randomFloat(6, 0, 999999999999.999999),
            'user_add_id' => fake()->numberBetween(1, 1000),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
