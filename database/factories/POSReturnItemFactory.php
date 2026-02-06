<?php

namespace Database\Factories;

use App\Models\POSOrderItem;
use App\Models\POSReturn;
use Illuminate\Database\Eloquent\Factories\Factory;

class POSReturnItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'pos_return_id' => fake()->numberBetween(1, 1000),
            'pos_order_item_id' => fake()->numberBetween(1, 1000),
            'product_variant_id' => fake()->numberBetween(1, 1000),
            'qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'unit_price' => fake()->randomFloat(6, 0, 999999999999.999999),
            'tax_rate_id' => fake()->numberBetween(1, 1000),
            'line_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'p_o_s_return_id' => POSReturn::factory(),
            'p_o_s_order_item_id' => POSOrderItem::factory(),
        ];
    }
}
