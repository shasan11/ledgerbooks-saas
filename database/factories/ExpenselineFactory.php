<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\Product;
use App\Models\TaxRate;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenselineFactory extends Factory
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
            'expense_id' => Expense::factory(),
            'product_id' => Product::factory(),
            'tax_rate_id' => TaxRate::factory(),
        ];
    }
}
