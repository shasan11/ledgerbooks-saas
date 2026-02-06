<?php

namespace Database\Factories;

use App\Models\;
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
            'branch_id' => fake()->word(),
            'store_id' => Store::factory(),
            'customer_profile_id' => ::factory(),
            'session_key' => fake()->regexify('[A-Za-z0-9]{120}'),
            'currency_id' => ::factory(),
            'subtotal' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'tax_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'grand_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
        ];
    }
}
