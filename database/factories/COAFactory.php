<?php

namespace Database\Factories;

use App\Models\;
use App\Models\Branch;
use App\Models\COA;
use Illuminate\Database\Eloquent\Factories\Factory;

class COAFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'name' => fake()->name(),
            'code' => fake()->regexify('[A-Za-z0-9]{60}'),
            'description' => fake()->text(),
            'parent_id' => fake()->word(),
            'account_type_id' => ::factory(),
            'is_group' => fake()->boolean(),
            'is_system' => fake()->boolean(),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
            'c_o_a_id' => COA::factory(),
        ];
    }
}
