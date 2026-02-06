<?php

namespace Database\Factories;

use App\Models\PosOrder;
use App\Models\ProductVariant;
use App\Models\TaxRate;
use Illuminate\Database\Eloquent\Factories\Factory;

class PosorderitemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_name' => fake()->regexify('[A-Za-z0-9]{200}'),
            'qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'unit_price' => fake()->randomFloat(6, 0, 999999999999.999999),
            'discount_amount' => fake()->randomFloat(6, 0, 999999999999.999999),
            'line_total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'created' => fake()->dateTime(),
            'updated' => fake()->dateTime(),
            'pos_order_id' => PosOrder::factory(),
            'product_variant_id' => ProductVariant::factory(),
            'tax_rate_id' => TaxRate::factory(),
        ];
    }
}
