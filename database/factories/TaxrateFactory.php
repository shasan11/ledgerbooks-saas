<?php

namespace Database\Factories;

use App\Models\TaxClass;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxrateFactory extends Factory
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
            'rate_percent' => fake()->randomFloat(6, 0, 999999999999.999999),
            'inclusive' => fake()->boolean(),
            'active_from' => fake()->word(),
            'active_to' => fake()->word(),
            'tax_class_id' => TaxClass::factory(),
            'user_add_id' => User::factory(),
        ];
    }
}
