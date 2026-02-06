<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
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
            'name' => fake()->name(),
            'code' => fake()->regexify('[A-Za-z0-9]{40}'),
            'description' => fake()->text(),
            'branch_id' => Branch::factory(),
            'user_add_id' => User::factory(),
        ];
    }
}
