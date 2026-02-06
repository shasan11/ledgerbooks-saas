<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderStatusHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'from_status' => fake()->regexify('[A-Za-z0-9]{50}'),
            'to_status' => fake()->regexify('[A-Za-z0-9]{50}'),
            'changed_at' => fake()->dateTime(),
            'changed_by_id' => fake()->word(),
            'note' => fake()->regexify('[A-Za-z0-9]{255}'),
            'user_id' => User::factory(),
        ];
    }
}
