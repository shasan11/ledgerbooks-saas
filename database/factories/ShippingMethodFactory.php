<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->numberBetween(1, 1000),
            'name' => fake()->name(),
            'code' => fake()->regexify('[A-Za-z0-9]{60}'),
            'description' => fake()->text(),
            'price' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'user_add_id' => fake()->numberBetween(1, 1000),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
