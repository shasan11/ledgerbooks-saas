<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\POSRegister;
use App\Models\POSShift;
use Illuminate\Database\Eloquent\Factories\Factory;

class POSOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->numberBetween(1, 1000),
            'order_no' => fake()->regexify('[A-Za-z0-9]{60}'),
            'order_date' => fake()->dateTime(),
            'register_id' => fake()->numberBetween(1, 1000),
            'shift_id' => fake()->numberBetween(1, 1000),
            'customer_id' => fake()->numberBetween(1, 1000),
            'status' => fake()->randomElement(["draft","posted","void"]),
            'subtotal' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'discount_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'tax_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
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
            'p_o_s_register_id' => POSRegister::factory(),
            'p_o_s_shift_id' => POSShift::factory(),
            'contact_id' => Contact::factory(),
        ];
    }
}
