<?php

namespace Database\Factories;

use App\Models\;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'user_id' => ::factory(),
            'contact_id' => ::factory(),
            'phone' => fake()->phoneNumber(),
            'dob' => fake()->date(),
            'notes' => fake()->text(),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
