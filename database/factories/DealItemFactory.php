<?php

namespace Database\Factories;

use App\Models\Deal;
use Illuminate\Database\Eloquent\Factories\Factory;

class DealItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->numberBetween(1, 1000),
            'deal_id' => Deal::factory(),
            'product_id' => fake()->numberBetween(1, 1000),
            'description' => fake()->text(),
            'qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'rate' => fake()->randomFloat(6, 0, 999999999999.999999),
            'discount_amount' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'tax_rate_id' => fake()->numberBetween(1, 1000),
            'line_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'user_add_id' => fake()->numberBetween(1, 1000),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
