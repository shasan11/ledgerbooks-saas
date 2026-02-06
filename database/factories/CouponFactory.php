<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
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
            'discount_type' => fake()->randomElement(["percent","fixed"]),
            'discount_value' => fake()->randomFloat(6, 0, 999999999999.999999),
            'min_order_amount' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'max_uses' => fake()->numberBetween(-10000, 10000),
            'max_uses_per_customer' => fake()->numberBetween(-10000, 10000),
            'valid_from' => fake()->dateTime(),
            'valid_to' => fake()->dateTime(),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
