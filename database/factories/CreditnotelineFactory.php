<?php

namespace Database\Factories;

use App\Models\CreditNote;
use App\Models\Product;
use App\Models\TaxRate;
use Illuminate\Database\Eloquent\Factories\Factory;

class CreditnotelineFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'description' => fake()->text(),
            'qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'rate' => fake()->randomFloat(6, 0, 999999999999.999999),
            'line_total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'created' => fake()->dateTime(),
            'updated' => fake()->dateTime(),
            'credit_note_id' => CreditNote::factory(),
            'product_id' => Product::factory(),
            'tax_rate_id' => TaxRate::factory(),
        ];
    }
}
