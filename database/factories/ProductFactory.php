<?php

namespace Database\Factories;

use App\Models\;
use App\Models\Branch;
use App\Models\COA;
use App\Models\ProductCategory;
use App\Models\UnitOfMeasurement;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'type' => fake()->randomElement(["goods","service"]),
            'name' => fake()->name(),
            'code' => fake()->regexify('[A-Za-z0-9]{80}'),
            'category_id' => fake()->word(),
            'tax_class_id' => ::factory(),
            'primary_unit_id' => fake()->word(),
            'hs_code' => fake()->regexify('[A-Za-z0-9]{40}'),
            'ecommerce_enabled' => fake()->boolean(),
            'pos_enabled' => fake()->boolean(),
            'description' => fake()->text(),
            'selling_price' => fake()->randomFloat(6, 0, 999999999999.999999),
            'purchase_price' => fake()->randomFloat(6, 0, 999999999999.999999),
            'sales_account_id' => fake()->word(),
            'purchase_account_id' => fake()->word(),
            'purchase_return_account_id' => fake()->word(),
            'valuation_method' => fake()->randomElement(["fifo","weighted_average"]),
            'track_inventory' => fake()->boolean(),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
            'product_category_id' => ProductCategory::factory(),
            'unit_of_measurement_id' => UnitOfMeasurement::factory(),
            'c_o_a_id' => COA::factory(),
        ];
    }
}
