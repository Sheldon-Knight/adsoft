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
        $transaction = Transaction::inRandomOrder()->first()->id;
        return [
            'account_id' => Account::inRandomOrder()->first()->id,
            'transaction_id' => $transaction->id,
            'description' => $$transaction->description,
            'debit' => $this->faker->numberBetween(0, 100),
            'credit' => $this->faker->numberBetween(0, 100),           
    
        ];
    }
}
