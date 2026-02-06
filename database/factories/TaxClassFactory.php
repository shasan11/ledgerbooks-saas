<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaxClassFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'code' => fake()->regexify('[A-Za-z0-9]{30}'),
            'description' => fake()->text(),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
