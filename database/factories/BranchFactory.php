<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code' => fake()->regexify('[A-Za-z0-9]{30}'),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->text(),
            'country' => fake()->country(),
            'city' => fake()->city(),
            'timezone' => fake()->regexify('[A-Za-z0-9]{64}'),
            'currency_id' => Currency::factory(),
            'is_head_office' => fake()->boolean(),
            'active' => fake()->boolean(),
        ];
    }
}
