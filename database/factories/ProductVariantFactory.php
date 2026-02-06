<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->numberBetween(1, 1000),
            'product_id' => Product::factory(),
            'sku' => fake()->regexify('[A-Za-z0-9]{120}'),
            'name' => fake()->name(),
            'option_summary' => fake()->regexify('[A-Za-z0-9]{255}'),
            'selling_price' => fake()->randomFloat(6, 0, 999999999999.999999),
            'purchase_price' => fake()->randomFloat(6, 0, 999999999999.999999),
            'user_add_id' => fake()->numberBetween(1, 1000),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
