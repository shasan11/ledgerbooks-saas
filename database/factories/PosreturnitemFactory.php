<?php

namespace Database\Factories;

use App\Models\PosOrderItem;
use App\Models\PosReturn;
use App\Models\ProductVariant;
use App\Models\TaxRate;
use Illuminate\Database\Eloquent\Factories\Factory;

class PosreturnitemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'unit_price' => fake()->randomFloat(6, 0, 999999999999.999999),
            'line_total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'created' => fake()->dateTime(),
            'updated' => fake()->dateTime(),
            'pos_order_item_id' => PosOrderItem::factory(),
            'pos_return_id' => PosReturn::factory(),
            'product_variant_id' => ProductVariant::factory(),
            'tax_rate_id' => TaxRate::factory(),
        ];
    }
}
