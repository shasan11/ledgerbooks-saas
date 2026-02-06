<?php

namespace Database\Factories;

use App\Models\POSOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class POSOrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'pos_order_id' => fake()->numberBetween(1, 1000),
            'product_variant_id' => fake()->numberBetween(1, 1000),
            'product_name' => fake()->regexify('[A-Za-z0-9]{200}'),
            'qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'unit_price' => fake()->randomFloat(6, 0, 999999999999.999999),
            'discount_amount' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'tax_rate_id' => fake()->numberBetween(1, 1000),
            'line_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'p_o_s_order_id' => POSOrder::factory(),
        ];
    }
}
