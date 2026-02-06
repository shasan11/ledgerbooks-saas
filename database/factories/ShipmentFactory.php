<?php

namespace Database\Factories;

use App\Models\;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->word(),
            'shipment_no' => fake()->regexify('[A-Za-z0-9]{60}'),
            'order_id' => Order::factory(),
            'shipping_method_id' => ::factory(),
            'tracking_no' => fake()->regexify('[A-Za-z0-9]{120}'),
            'shipped_at' => fake()->dateTime(),
            'delivered_at' => fake()->dateTime(),
            'status' => fake()->randomElement(["packed","shipped","delivered","returned"]),
            'approved' => fake()->boolean(),
            'approved_at' => fake()->dateTime(),
            'approved_by_id' => fake()->word(),
            'voided_reason' => fake()->regexify('[A-Za-z0-9]{255}'),
            'voided_at' => fake()->dateTime(),
            'exchange_rate' => fake()->randomFloat(6, 0, 999999999999.999999),
            'total' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'note' => fake()->text(),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
