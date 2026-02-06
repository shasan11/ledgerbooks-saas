<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\WarehouseTransfer;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehousetransferitemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'note' => fake()->regexify('[A-Za-z0-9]{255}'),
            'created' => fake()->dateTime(),
            'updated' => fake()->dateTime(),
            'product_variant_id' => ProductVariant::factory(),
            'warehouse_transfer_id' => WarehouseTransfer::factory(),
        ];
    }
}
