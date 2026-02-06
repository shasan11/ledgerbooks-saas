<?php

namespace Database\Factories;

use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'shipment_id' => Shipment::factory(),
            'order_item_id' => fake()->numberBetween(1, 1000),
            'qty' => fake()->randomFloat(6, 0, 999999999999.999999),
        ];
    }
}
