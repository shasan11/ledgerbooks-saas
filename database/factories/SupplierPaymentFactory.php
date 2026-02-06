<?php

namespace Database\Factories;

use App\Models\;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierPaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->word(),
            'payment_no' => fake()->regexify('[A-Za-z0-9]{50}'),
            'payment_date' => fake()->date(),
            'supplier_id' => fake()->word(),
            'currency_id' => ::factory(),
            'amount' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'method' => fake()->randomElement(["cash","bank","cheque","online"]),
            'bank_account_id' => ::factory(),
            'reference' => fake()->regexify('[A-Za-z0-9]{100}'),
            'status' => fake()->randomElement(["draft","posted","void"]),
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
            'contact_id' => Contact::factory(),
        ];
    }
}
