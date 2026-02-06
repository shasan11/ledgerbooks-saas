<?php

namespace Database\Factories;

use App\Models\WarehouseTransfer;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseTransferItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'warehouse_transfer_id' => WarehouseTransfer::factory(),
            'product_variant_id' => fake()->numberBetween(1, 1000),
            'qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'note' => fake()->regexify('[A-Za-z0-9]{255}'),
        ];
    }
}
