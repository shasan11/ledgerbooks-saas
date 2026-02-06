<?php

namespace Database\Factories;

use App\Models\ProductionOrder;
use App\Models\RawMaterialVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductioninputFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'qty_required' => fake()->randomFloat(6, 0, 999999999999.999999),
            'qty_consumed' => fake()->randomFloat(6, 0, 999999999999.999999),
            'created' => fake()->dateTime(),
            'updated' => fake()->dateTime(),
            'production_order_id' => ProductionOrder::factory(),
            'raw_material_variant_id' => RawMaterialVariant::factory(),
        ];
    }
}
