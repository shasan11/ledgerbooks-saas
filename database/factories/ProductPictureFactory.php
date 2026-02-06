<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductPictureFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->word(),
            'product_id' => Product::factory(),
            'image' => fake()->regexify('[A-Za-z0-9]{255}'),
            'is_main' => fake()->boolean(),
            'description' => fake()->text(),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
