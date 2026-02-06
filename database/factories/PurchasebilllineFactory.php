<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\PurchaseBill;
use App\Models\TaxRate;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchasebilllineFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_name' => fake()->regexify('[A-Za-z0-9]{200}'),
            'qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'rate' => fake()->randomFloat(6, 0, 999999999999.999999),
            'discount_amount' => fake()->randomFloat(6, 0, 999999999999.999999),
            'line_total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'created' => fake()->dateTime(),
            'updated' => fake()->dateTime(),
            'product_id' => Product::factory(),
            'purchase_bill_id' => PurchaseBill::factory(),
            'tax_rate_id' => TaxRate::factory(),
        ];
    }
}
