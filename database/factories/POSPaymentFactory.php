<?php

namespace Database\Factories;

use App\Models\POSOrder;
use App\Models\POSPaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class POSPaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->numberBetween(1, 1000),
            'pos_order_id' => fake()->numberBetween(1, 1000),
            'method_id' => fake()->numberBetween(1, 1000),
            'amount' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'reference' => fake()->regexify('[A-Za-z0-9]{120}'),
            'status' => fake()->randomElement(["posted","void"]),
            'approved' => fake()->boolean(),
            'approved_at' => fake()->dateTime(),
            'approved_by_id' => fake()->numberBetween(1, 1000),
            'voided_reason' => fake()->regexify('[A-Za-z0-9]{255}'),
            'voided_at' => fake()->dateTime(),
            'exchange_rate' => fake()->randomFloat(6, 0, 999999999999.999999),
            'total' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'note' => fake()->text(),
            'user_add_id' => fake()->numberBetween(1, 1000),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
            'p_o_s_order_id' => POSOrder::factory(),
            'p_o_s_payment_method_id' => POSPaymentMethod::factory(),
        ];
    }
}
