<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\UserAdd;
use Illuminate\Database\Eloquent\Factories\Factory;

class PosdiscountprofileFactory extends Factory
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
            'discount_type' => fake()->regexify('[A-Za-z0-9]{10}'),
            'value' => fake()->randomFloat(6, 0, 999999999999.999999),
            'branch_id' => Branch::factory(),
            'user_add_id' => UserAdd::factory(),
        ];
    }
}
