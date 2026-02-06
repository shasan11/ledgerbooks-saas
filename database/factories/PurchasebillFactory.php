<?php

namespace Database\Factories;

use App\Models\ApprovedBy;
use App\Models\Branch;
use App\Models\Currency;
use App\Models\Supplier;
use App\Models\UserAdd;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchasebillFactory extends Factory
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
            'bill_no' => fake()->regexify('[A-Za-z0-9]{50}'),
            'bill_date' => fake()->word(),
            'due_date' => fake()->word(),
            'status' => fake()->regexify('[A-Za-z0-9]{20}'),
            'subtotal' => fake()->randomFloat(6, 0, 999999999999.999999),
            'discount_total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'tax_total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'grand_total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'balance_due' => fake()->randomFloat(6, 0, 999999999999.999999),
            'total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'note' => fake()->word(),
            'approved_by_id' => ApprovedBy::factory(),
            'branch_id' => Branch::factory(),
            'currency_id' => Currency::factory(),
            'supplier_id' => Supplier::factory(),
            'user_add_id' => UserAdd::factory(),
        ];
    }
}
