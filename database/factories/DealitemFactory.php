<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Deal;
use App\Models\Product;
use App\Models\TaxRate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DealitemFactory extends Factory
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
            'description' => fake()->text(),
            'qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'rate' => fake()->randomFloat(6, 0, 999999999999.999999),
            'discount_amount' => fake()->randomFloat(6, 0, 999999999999.999999),
            'line_total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'branch_id' => Branch::factory(),
            'deal_id' => Deal::factory(),
            'product_id' => Product::factory(),
            'tax_rate_id' => TaxRate::factory(),
            'user_add_id' => User::factory(),
        ];
    }
}
