<?php

namespace Database\Factories;

use App\Models\Parent;
use App\Models\UserAdd;
use Illuminate\Database\Eloquent\Factories\Factory;

class MasterdataFactory extends Factory
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
            'key' => fake()->regexify('[A-Za-z0-9]{80}'),
            'name' => fake()->name(),
            'value' => fake()->word(),
            'is_boolean' => fake()->boolean(),
            'parent_id' => Parent::factory(),
            'user_add_id' => UserAdd::factory(),
        ];
    }
}
