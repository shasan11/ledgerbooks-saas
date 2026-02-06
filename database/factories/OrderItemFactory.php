<?php

namespace Database\Factories;

use App\Models\;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_variant_id' => ::factory(),
            'product_name' => fake()->regexify('[A-Za-z0-9]{200}'),
            'qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'unit_price' => fake()->randomFloat(6, 0, 999999999999.999999),
            'tax_rate_id' => ::factory(),
            'line_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
        ];
    }
}
