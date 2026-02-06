<?php

namespace Database\Factories;

use App\Models\CustomerPayment;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerpaymentallocationFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'allocated_amount' => fake()->randomFloat(6, 0, 999999999999.999999),
            'note' => fake()->regexify('[A-Za-z0-9]{255}'),
            'created' => fake()->dateTime(),
            'updated' => fake()->dateTime(),
            'customer_payment_id' => CustomerPayment::factory(),
            'invoice_id' => Invoice::factory(),
        ];
    }
}
