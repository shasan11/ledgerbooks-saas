<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductvariantFactory extends Factory
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
            'sku' => fake()->regexify('[A-Za-z0-9]{120}'),
            'name' => fake()->name(),
            'option_summary' => fake()->regexify('[A-Za-z0-9]{255}'),
            'selling_price' => fake()->randomFloat(6, 0, 999999999999.999999),
            'purchase_price' => fake()->randomFloat(6, 0, 999999999999.999999),
            'branch_id' => Branch::factory(),
            'product_id' => Product::factory(),
            'user_add_id' => User::factory(),
        ];
    }
}
