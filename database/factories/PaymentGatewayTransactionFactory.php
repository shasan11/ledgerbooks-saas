<?php

namespace Database\Factories;

use App\Models\OrderPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentGatewayTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->numberBetween(1, 1000),
            'order_payment_id' => OrderPayment::factory(),
            'gateway' => fake()->regexify('[A-Za-z0-9]{80}'),
            'transaction_id' => fake()->regexify('[A-Za-z0-9]{150}'),
            'request_payload' => fake()->text(),
            'response_payload' => fake()->text(),
            'status' => fake()->regexify('[A-Za-z0-9]{50}'),
            'processed_at' => fake()->dateTime(),
            'user_add_id' => fake()->numberBetween(1, 1000),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
