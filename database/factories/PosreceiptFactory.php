<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\PosOrder;
use App\Models\UserAdd;
use Illuminate\Database\Eloquent\Factories\Factory;

class PosreceiptFactory extends Factory
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
            'receipt_no' => fake()->regexify('[A-Za-z0-9]{60}'),
            'printed_at' => fake()->dateTime(),
            'payload' => fake()->word(),
            'branch_id' => Branch::factory(),
            'pos_order_id' => PosOrder::factory(),
            'user_add_id' => UserAdd::factory(),
        ];
    }
}
