<?php

namespace Database\Factories;

use App\Models\UserAdd;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccounttypeFactory extends Factory
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
            'category' => fake()->regexify('[A-Za-z0-9]{20}'),
            'normal_balance' => fake()->regexify('[A-Za-z0-9]{2}'),
            'user_add_id' => UserAdd::factory(),
        ];
    }
}
