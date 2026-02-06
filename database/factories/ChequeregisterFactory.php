<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\BankAccount;
use App\Models\Branch;
use App\Models\CoaAccount;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChequeregisterFactory extends Factory
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
            'approved' => fake()->boolean(),
            'approved_at' => fake()->dateTime(),
            'voided_reason' => fake()->word(),
            'voided_at' => fake()->dateTime(),
            'exchange_rate' => fake()->randomFloat(6, 0, 999999999999.999999),
            'cheque_no' => fake()->regexify('[A-Za-z0-9]{80}'),
            'cheque_date' => fake()->word(),
            'received_date' => fake()->word(),
            'amount' => fake()->randomFloat(6, 0, 999999999999.999999),
            'status' => fake()->regexify('[A-Za-z0-9]{12}'),
            'memo' => fake()->regexify('[A-Za-z0-9]{255}'),
            'total' => fake()->randomFloat(6, 0, 999999999999.999999),
            'note' => fake()->word(),
            'approved_by_id' => User::factory(),
            'bank_account_id' => BankAccount::factory(),
            'branch_id' => Branch::factory(),
            'contact_id' => Contact::factory(),
            'user_add_id' => User::factory(),
            'coa_account_id' => CoaAccount::factory(),
        ];
    }
}
