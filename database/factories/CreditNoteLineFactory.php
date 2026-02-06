<?php

namespace Database\Factories;

use App\Models\;
use App\Models\CreditNote;
use Illuminate\Database\Eloquent\Factories\Factory;

class CreditNoteLineFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'credit_note_id' => CreditNote::factory(),
            'product_id' => ::factory(),
            'description' => fake()->text(),
            'qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'rate' => fake()->randomFloat(6, 0, 999999999999.999999),
            'tax_rate_id' => ::factory(),
            'line_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
        ];
    }
}
