<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\ProductionOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionInputFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'production_order_id' => ProductionOrder::factory(),
            'raw_material_variant_id' => fake()->word(),
            'qty_required' => fake()->randomFloat(6, 0, 999999999999.999999),
            'qty_consumed' => fake()->randomFloat(6, 0, 999999999999.999999),
            'product_variant_id' => ProductVariant::factory(),
        ];
    }
}
