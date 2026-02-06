<?php

namespace Database\Factories;

use App\Models\InventoryAdjustment;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryAdjustmentItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'inventory_adjustment_id' => InventoryAdjustment::factory(),
            'product_variant_id' => fake()->numberBetween(1, 1000),
            'qty_change' => fake()->randomFloat(6, 0, 999999999999.999999),
            'unit_cost' => fake()->randomFloat(6, 0, 999999999999.999999),
            'note' => fake()->regexify('[A-Za-z0-9]{255}'),
        ];
    }
}
