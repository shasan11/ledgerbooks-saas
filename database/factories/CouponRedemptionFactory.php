<?php

namespace Database\Factories;

use App\Models\;
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
            'customer_profile_id' => ::factory(),
            'order_id' => ::factory(),
            'redeemed_at' => fake()->dateTime(),
        ];
    }
}
