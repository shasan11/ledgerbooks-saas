<?php

namespace Database\Factories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseLineFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'expense_id' => Expense::factory(),
            'product_id' => fake()->numberBetween(1, 1000),
            'product_name' => fake()->regexify('[A-Za-z0-9]{200}'),
            'qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'rate' => fake()->randomFloat(6, 0, 999999999999.999999),
            'discount_amount' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'tax_rate_id' => fake()->numberBetween(1, 1000),
            'line_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
        ];
    }
}
