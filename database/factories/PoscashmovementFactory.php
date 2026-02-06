<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Branch;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

class PoscashmovementFactory extends Factory
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
            'type' => fake()->regexify('[A-Za-z0-9]{5}'),
            'amount' => fake()->randomFloat(6, 0, 999999999999.999999),
            'reason' => fake()->regexify('[A-Za-z0-9]{255}'),
            'total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'note' => fake()->word(),
            'approved_by_id' => User::factory(),
            'branch_id' => Branch::factory(),
            'user_add_id' => User::factory(),
            'shift_id' => Shift::factory(),
        ];
    }
}
