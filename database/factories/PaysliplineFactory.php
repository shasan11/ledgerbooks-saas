<?php

namespace Database\Factories;

use App\Models\Component;
use App\Models\Payslip;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaysliplineFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'amount' => fake()->randomFloat(6, 0, 999999999999.999999),
            'created' => fake()->dateTime(),
            'updated' => fake()->dateTime(),
            'payslip_id' => Payslip::factory(),
            'component_id' => Component::factory(),
        ];
    }
}
