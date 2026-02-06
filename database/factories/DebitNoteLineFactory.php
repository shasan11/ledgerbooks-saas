<?php

namespace Database\Factories;

use App\Models\DebitNote;
use Illuminate\Database\Eloquent\Factories\Factory;

class DebitNoteLineFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'debit_note_id' => DebitNote::factory(),
            'product_id' => fake()->numberBetween(1, 1000),
            'description' => fake()->text(),
            'qty' => fake()->randomFloat(6, 0, 999999999999.999999),
            'rate' => fake()->randomFloat(6, 0, 999999999999.999999),
            'tax_rate_id' => fake()->numberBetween(1, 1000),
            'line_total' => fake()->randomFloat(2, 0, 9999999999999999.99),
        ];
    }
}
