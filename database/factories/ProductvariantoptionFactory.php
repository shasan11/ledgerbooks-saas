<?php

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\Option;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductvariantoptionFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'created' => fake()->dateTime(),
            'updated' => fake()->dateTime(),
            'product_variant_id' => ProductVariant::factory(),
            'attribute_id' => Attribute::factory(),
            'option_id' => Option::factory(),
        ];
    }
}
