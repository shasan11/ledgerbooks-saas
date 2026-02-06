<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\VariantAttribute;
use App\Models\VariantAttributeOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_variant_id' => ProductVariant::factory(),
            'attribute_id' => fake()->numberBetween(1, 1000),
            'option_id' => fake()->numberBetween(1, 1000),
            'variant_attribute_id' => VariantAttribute::factory(),
            'variant_attribute_option_id' => VariantAttributeOption::factory(),
        ];
    }
}
