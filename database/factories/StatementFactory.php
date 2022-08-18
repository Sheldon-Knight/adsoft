<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Statement>
 */
class StatementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'account_id' => Account::inRandomOrder()->first()->id,
            'transaction_id' => Transaction::inRandomOrder()->first()->id,
            'description' => $this->faker->text,
            'type' => $this->faker->randomElement(['credit', 'debit']),
            'amount' => $this->faker->numberBetween(1, 100),
        ];
    }
}
