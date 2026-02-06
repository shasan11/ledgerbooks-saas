<?php

namespace Database\Factories;

use App\Models\POSOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class POSReceiptFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->word(),
            'pos_order_id' => fake()->word(),
            'receipt_no' => fake()->regexify('[A-Za-z0-9]{60}'),
            'printed_at' => fake()->dateTime(),
            'payload' => fake()->text(),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
            'p_o_s_order_id' => POSOrder::factory(),
        ];
    }
}
