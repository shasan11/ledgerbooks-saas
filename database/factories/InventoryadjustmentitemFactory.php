<?php

namespace Database\Factories;

use App\Models\InventoryAdjustment;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryadjustmentitemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'qty_change' => fake()->randomFloat(6, 0, 999999999999.999999),
            'unit_cost' => fake()->randomFloat(6, 0, 999999999999.999999),
            'note' => fake()->regexify('[A-Za-z0-9]{255}'),
            'created' => fake()->dateTime(),
            'updated' => fake()->dateTime(),
            'inventory_adjustment_id' => InventoryAdjustment::factory(),
            'product_variant_id' => ProductVariant::factory(),
        ];
    }
}
