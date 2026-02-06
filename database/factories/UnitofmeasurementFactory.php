<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\UserAdd;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitofmeasurementFactory extends Factory
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
            'short_name' => fake()->regexify('[A-Za-z0-9]{30}'),
            'description' => fake()->text(),
            'accept_fraction' => fake()->boolean(),
            'branch_id' => Branch::factory(),
            'user_add_id' => UserAdd::factory(),
        ];
    }
}
