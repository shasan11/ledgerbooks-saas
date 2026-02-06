<?php

namespace Database\Factories;

use App\Models\MasterData;
use Illuminate\Database\Eloquent\Factories\Factory;

class MasterDataFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'key' => fake()->regexify('[A-Za-z0-9]{80}'),
            'name' => fake()->name(),
            'value' => fake()->text(),
            'is_boolean' => fake()->boolean(),
            'parent_id' => fake()->numberBetween(1, 1000),
            'user_add_id' => fake()->numberBetween(1, 1000),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
            'master_data_id' => MasterData::factory(),
        ];
    }
}
