<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\TaxRate;
use App\Models\UserAdd;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostaxprofileFactory extends Factory
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
            'branch_id' => Branch::factory(),
            'tax_rate_id' => TaxRate::factory(),
            'user_add_id' => UserAdd::factory(),
        ];
    }
}
