<?php

namespace Database\Factories;

use App\Models\ApprovedBy;
use App\Models\BankAccount;
use App\Models\Branch;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\UserAdd;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerpaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'created' => fake()->dateTime(),
            'updated' => fake()->dateTime(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
            'approved' => fake()->boolean(),
            'approved_at' => fake()->dateTime(),
            'voided_reason' => fake()->word(),
            'voided_at' => fake()->dateTime(),
            'exchange_rate' => fake()->randomFloat(6, 0, 999999999999.999999),
            'payment_no' => fake()->regexify('[A-Za-z0-9]{50}'),
            'payment_date' => fake()->word(),
            'amount' => fake()->randomFloat(6, 0, 999999999999.999999),
            'method' => fake()->regexify('[A-Za-z0-9]{20}'),
            'reference' => fake()->regexify('[A-Za-z0-9]{100}'),
            'status' => fake()->regexify('[A-Za-z0-9]{20}'),
            'total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'note' => fake()->word(),
            'approved_by_id' => ApprovedBy::factory(),
            'bank_account_id' => BankAccount::factory(),
            'branch_id' => Branch::factory(),
            'currency_id' => Currency::factory(),
            'customer_id' => Customer::factory(),
            'user_add_id' => UserAdd::factory(),
        ];
    }
}
