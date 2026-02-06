<?php

namespace Database\Factories;

use App\Models\POSShift;
use Illuminate\Database\Eloquent\Factories\Factory;

class POSSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->word(),
            'shift_id' => fake()->word(),
            'device_id' => fake()->regexify('[A-Za-z0-9]{120}'),
            'started_at' => fake()->dateTime(),
            'ended_at' => fake()->dateTime(),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
            'p_o_s_shift_id' => POSShift::factory(),
        ];
    }
}
