<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\ProductionOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionOutputFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'production_order_id' => ProductionOrder::factory(),
            'finished_good_variant_id' => fake()->word(),
            'qty_produced' => fake()->randomFloat(6, 0, 999999999999.999999),
            'product_variant_id' => ProductVariant::factory(),
        ];
    }
}
