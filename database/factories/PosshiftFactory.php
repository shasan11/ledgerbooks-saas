<?php

namespace Database\Factories;

use App\Models\ApprovedBy;
use App\Models\Branch;
use App\Models\ClosedBy;
use App\Models\OpenedBy;
use App\Models\Register;
use App\Models\UserAdd;
use Illuminate\Database\Eloquent\Factories\Factory;

class PosshiftFactory extends Factory
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
            'opened_at' => fake()->dateTime(),
            'closed_at' => fake()->dateTime(),
            'opening_cash' => fake()->randomFloat(6, 0, 999999999999.999999),
            'closing_cash' => fake()->randomFloat(6, 0, 999999999999.999999),
            'status' => fake()->regexify('[A-Za-z0-9]{10}'),
            'total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'note' => fake()->word(),
            'approved_by_id' => ApprovedBy::factory(),
            'branch_id' => Branch::factory(),
            'closed_by_id' => ClosedBy::factory(),
            'opened_by_id' => OpenedBy::factory(),
            'register_id' => Register::factory(),
            'user_add_id' => UserAdd::factory(),
        ];
    }
}
