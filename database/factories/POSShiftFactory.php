<?php

namespace Database\Factories;

use App\Models\POSRegister;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class POSShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->numberBetween(1, 1000),
            'register_id' => fake()->numberBetween(1, 1000),
            'opened_at' => fake()->dateTime(),
            'opened_by_id' => fake()->numberBetween(1, 1000),
            'closed_at' => fake()->dateTime(),
            'closed_by_id' => fake()->numberBetween(1, 1000),
            'opening_cash' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'closing_cash' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'status' => fake()->randomElement(["open","closed"]),
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
            'user_id' => User::factory(),
        ];
    }
}
