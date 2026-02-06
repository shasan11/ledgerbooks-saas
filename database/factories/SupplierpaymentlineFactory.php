<?php

namespace Database\Factories;

use App\Models\PurchaseBill;
use App\Models\SupplierPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierpaymentlineFactory extends Factory
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
            'purchase_bill_id' => PurchaseBill::factory(),
            'supplier_payment_id' => SupplierPayment::factory(),
        ];
    }
}
