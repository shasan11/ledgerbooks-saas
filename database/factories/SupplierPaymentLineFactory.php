<?php

namespace Database\Factories;

use App\Models\SupplierPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierPaymentLineFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'supplier_payment_id' => SupplierPayment::factory(),
            'purchase_bill_id' => fake()->numberBetween(1, 1000),
            'allocated_amount' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'note' => fake()->regexify('[A-Za-z0-9]{255}'),
        ];
    }
}
