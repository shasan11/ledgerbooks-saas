<?php

namespace Database\Factories;

use App\Models\TaxClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxRateFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'tax_class_id' => TaxClass::factory(),
            'name' => fake()->name(),
            'rate_percent' => fake()->randomFloat(4, 0, 9999.9999),
            'inclusive' => fake()->boolean(),
            'active_from' => fake()->date(),
            'active_to' => fake()->date(),
            'user_add_id' => fake()->numberBetween(1, 1000),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
