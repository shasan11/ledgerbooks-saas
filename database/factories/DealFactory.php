<?php

namespace Database\Factories;

use App\Models\;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class DealFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'code' => fake()->regexify('[A-Za-z0-9]{50}'),
            'title' => fake()->sentence(4),
            'contact_id' => ::factory(),
            'stage' => fake()->randomElement(["lead","qualified","proposal","won","lost"]),
            'expected_close' => fake()->date(),
            'probability' => fake()->numberBetween(-10000, 10000),
            'currency_id' => ::factory(),
            'expected_value' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'source' => fake()->regexify('[A-Za-z0-9]{80}'),
            'owner_id' => fake()->word(),
            'description' => fake()->text(),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
