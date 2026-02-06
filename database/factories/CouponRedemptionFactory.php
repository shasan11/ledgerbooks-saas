<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponRedemptionFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'coupon_id' => Coupon::factory(),
            'customer_profile_id' => fake()->numberBetween(1, 1000),
            'order_id' => fake()->numberBetween(1, 1000),
            'redeemed_at' => fake()->dateTime(),
        ];
    }
}
