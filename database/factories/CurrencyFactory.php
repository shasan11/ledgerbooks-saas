<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
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
            'code' => fake()->regexify('[A-Za-z0-9]{10}'),
            'symbol' => fake()->regexify('[A-Za-z0-9]{10}'),
            'decimal_places' => fake()->numberBetween(-10000, 10000),
            'is_base' => fake()->boolean(),
            'user_add_id' => User::factory(),
        ];
    }
}
