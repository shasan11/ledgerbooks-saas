<?php

namespace Database\Factories;

use App\Models\FinishedGoodVariant;
use App\Models\ProductionOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionoutputFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'qty_produced' => fake()->randomFloat(6, 0, 999999999999.999999),
            'created' => fake()->dateTime(),
            'updated' => fake()->dateTime(),
            'production_order_id' => ProductionOrder::factory(),
            'finished_good_variant_id' => FinishedGoodVariant::factory(),
        ];
    }
}
