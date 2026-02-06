<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PossessionFactory extends Factory
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
            'device_id' => fake()->regexify('[A-Za-z0-9]{120}'),
            'started_at' => fake()->dateTime(),
            'ended_at' => fake()->dateTime(),
            'branch_id' => Branch::factory(),
            'user_add_id' => User::factory(),
            'shift_id' => Shift::factory(),
        ];
    }
}
