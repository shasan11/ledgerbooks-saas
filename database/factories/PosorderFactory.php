<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Register;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

class PosorderFactory extends Factory
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
            'order_no' => fake()->regexify('[A-Za-z0-9]{60}'),
            'order_date' => fake()->dateTime(),
            'status' => fake()->regexify('[A-Za-z0-9]{10}'),
            'subtotal' => fake()->randomFloat(6, 0, 999999999999.999999),
            'discount_total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'tax_total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'grand_total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'note' => fake()->word(),
            'approved_by_id' => User::factory(),
            'branch_id' => Branch::factory(),
            'customer_id' => Customer::factory(),
            'user_add_id' => User::factory(),
            'register_id' => Register::factory(),
            'shift_id' => Shift::factory(),
        ];
    }
}
