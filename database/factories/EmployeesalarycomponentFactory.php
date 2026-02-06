<?php

namespace Database\Factories;

use App\Models\Component;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeesalarycomponentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'amount' => fake()->randomFloat(6, 0, 999999999999.999999),
            'active' => fake()->boolean(),
            'created' => fake()->dateTime(),
            'updated' => fake()->dateTime(),
            'employee_id' => Employee::factory(),
            'component_id' => Component::factory(),
        ];
    }
}
