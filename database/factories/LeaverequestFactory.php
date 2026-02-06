<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaverequestFactory extends Factory
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
            'from_date' => fake()->word(),
            'to_date' => fake()->word(),
            'days' => fake()->randomFloat(6, 0, 999999999999.999999),
            'reason' => fake()->word(),
            'status' => fake()->regexify('[A-Za-z0-9]{20}'),
            'total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'note' => fake()->word(),
            'approved_by_id' => User::factory(),
            'branch_id' => Branch::factory(),
            'employee_id' => Employee::factory(),
            'user_add_id' => User::factory(),
            'leave_type_id' => LeaveType::factory(),
        ];
    }
}
