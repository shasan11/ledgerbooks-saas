<?php

namespace Database\Factories;

use App\Models\;
use App\Models\Refund;
use Illuminate\Database\Eloquent\Factories\Factory;

class RefundItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'refund_id' => Refund::factory(),
            'order_item_id' => ::factory(),
            'qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'amount' => fake()->randomFloat(2, 0, 9999999999999999.99),
        ];
    }
}
