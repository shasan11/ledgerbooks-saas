<?php

namespace Database\Factories;

use App\Models\;
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
            'branch_id' => fake()->word(),
            'store_id' => Store::factory(),
            'product_id' => ::factory(),
            'customer_profile_id' => ::factory(),
            'rating' => fake()->numberBetween(-8, 8),
            'title' => fake()->sentence(4),
            'review' => fake()->text(),
            'is_approved' => fake()->boolean(),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
