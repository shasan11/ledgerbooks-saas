<?php

namespace Database\Factories;

use App\Models\CustomerAddress;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->numberBetween(1, 1000),
            'store_id' => Store::factory(),
            'order_no' => fake()->regexify('[A-Za-z0-9]{60}'),
            'customer_profile_id' => fake()->numberBetween(1, 1000),
            'customer_email' => fake()->regexify('[A-Za-z0-9]{150}'),
            'shipping_address_id' => fake()->numberBetween(1, 1000),
            'billing_address_id' => fake()->numberBetween(1, 1000),
            'currency_id' => fake()->numberBetween(1, 1000),
            'status' => fake()->randomElement(["pending","paid","processing","shipped","delivered","cancelled","refunded"]),
            'subtotal' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'discount_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'tax_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'shipping_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'grand_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
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
            'customer_address_id' => CustomerAddress::factory(),
        ];
    }
}
