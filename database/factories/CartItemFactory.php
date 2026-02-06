<?php

namespace Database\Factories;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'cart_id' => Cart::factory(),
            'product_variant_id' => fake()->numberBetween(1, 1000),
            'qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'unit_price' => fake()->randomFloat(6, 0, 999999999999.999999),
            'tax_rate_id' => fake()->numberBetween(1, 1000),
            'line_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
        ];
    }
}
