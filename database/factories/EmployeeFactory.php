<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
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
            'code' => fake()->regexify('[A-Za-z0-9]{60}'),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'employment_type' => fake()->regexify('[A-Za-z0-9]{20}'),
            'join_date' => fake()->word(),
            'exit_date' => fake()->word(),
            'basic_salary' => fake()->randomFloat(6, 0, 999999999999.999999),
            'notes' => fake()->word(),
            'branch_id' => Branch::factory(),
            'department_id' => Department::factory(),
            'designation_id' => Designation::factory(),
            'user_id' => User::factory(),
            'user_add_id' => User::factory(),
        ];
    }
}
