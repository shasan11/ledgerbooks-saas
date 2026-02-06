<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->numberBetween(1, 1000),
            'store_id' => Store::factory(),
            'customer_profile_id' => fake()->numberBetween(1, 1000),
            'session_key' => fake()->regexify('[A-Za-z0-9]{120}'),
            'currency_id' => fake()->numberBetween(1, 1000),
            'subtotal' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'tax_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'grand_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
        ];
    }
}
