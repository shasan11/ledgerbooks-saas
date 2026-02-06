<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Category;
use App\Models\PrimaryUnit;
use App\Models\PurchaseAccount;
use App\Models\PurchaseReturnAccount;
use App\Models\SalesAccount;
use App\Models\TaxClass;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
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
            'type' => fake()->regexify('[A-Za-z0-9]{10}'),
            'name' => fake()->name(),
            'code' => fake()->regexify('[A-Za-z0-9]{80}'),
            'hs_code' => fake()->regexify('[A-Za-z0-9]{40}'),
            'ecommerce_enabled' => fake()->boolean(),
            'pos_enabled' => fake()->boolean(),
            'description' => fake()->text(),
            'selling_price' => fake()->randomFloat(6, 0, 999999999999.999999),
            'purchase_price' => fake()->randomFloat(6, 0, 999999999999.999999),
            'valuation_method' => fake()->regexify('[A-Za-z0-9]{20}'),
            'track_inventory' => fake()->boolean(),
            'branch_id' => Branch::factory(),
            'purchase_account_id' => PurchaseAccount::factory(),
            'purchase_return_account_id' => PurchaseReturnAccount::factory(),
            'sales_account_id' => SalesAccount::factory(),
            'tax_class_id' => TaxClass::factory(),
            'user_add_id' => User::factory(),
            'category_id' => Category::factory(),
            'primary_unit_id' => PrimaryUnit::factory(),
        ];
    }
}
