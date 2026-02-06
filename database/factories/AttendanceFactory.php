<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
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
            'date' => fake()->word(),
            'check_in' => fake()->dateTime(),
            'check_out' => fake()->dateTime(),
            'total_minutes' => fake()->numberBetween(-10000, 10000),
            'note' => fake()->word(),
            'branch_id' => Branch::factory(),
            'user_add_id' => User::factory(),
            'employee_id' => Employee::factory(),
        ];
    }
}
