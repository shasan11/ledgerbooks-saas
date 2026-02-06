<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->numberBetween(1, 1000),
            'store_id' => Store::factory(),
            'product_id' => fake()->numberBetween(1, 1000),
            'customer_profile_id' => fake()->numberBetween(1, 1000),
            'rating' => fake()->numberBetween(-8, 8),
            'title' => fake()->sentence(4),
            'review' => fake()->text(),
            'is_approved' => fake()->boolean(),
            'user_add_id' => fake()->numberBetween(1, 1000),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
