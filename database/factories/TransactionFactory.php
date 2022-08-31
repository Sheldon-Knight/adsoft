<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'transaction_id' => $this->faker->uuid,
            'account_id' => Account::inRandomOrder()->first()->id,
            'description' => $this->faker->sentence,
            'type' => $this->faker->randomElement(['credit', 'debit']),
            'amount' => $this->faker->numberBetween(1, 100),
        ];
    }
}
