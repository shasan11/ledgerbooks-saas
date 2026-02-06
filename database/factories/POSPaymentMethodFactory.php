<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class POSPaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->word(),
            'name' => fake()->name(),
            'type' => fake()->randomElement(["cash","card","bank","wallet","cod"]),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
