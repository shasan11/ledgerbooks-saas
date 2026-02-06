<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\POSOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class POSReturnFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->word(),
            'return_no' => fake()->regexify('[A-Za-z0-9]{60}'),
            'return_date' => fake()->dateTime(),
            'pos_order_id' => fake()->word(),
            'customer_id' => fake()->word(),
            'reason' => fake()->regexify('[A-Za-z0-9]{255}'),
            'status' => fake()->randomElement(["draft","posted","void"]),
            'total' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'approved' => fake()->boolean(),
            'approved_at' => fake()->dateTime(),
            'approved_by_id' => fake()->word(),
            'voided_reason' => fake()->regexify('[A-Za-z0-9]{255}'),
            'voided_at' => fake()->dateTime(),
            'exchange_rate' => fake()->randomFloat(6, 0, 999999999999.999999),
            'note' => fake()->text(),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
            'p_o_s_order_id' => POSOrder::factory(),
            'contact_id' => Contact::factory(),
        ];
    }
}
