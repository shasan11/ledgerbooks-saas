<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\UserAdd;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
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
            'code' => fake()->regexify('[A-Za-z0-9]{30}'),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->word(),
            'country' => fake()->country(),
            'city' => fake()->city(),
            'timezone' => fake()->regexify('[A-Za-z0-9]{64}'),
            'is_head_office' => fake()->boolean(),
            'user_add_id' => UserAdd::factory(),
            'currency_id' => Currency::factory(),
        ];
    }
}
