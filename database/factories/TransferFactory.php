<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transfer>
 */
class TransferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

       $fromAccount = Account::inRandomOrder()->first()->id;
       
       $toAccount = Account::inRandomOrder()->first()->id;

       if($fromAccount == $toAccount){
           $toAccount = Account::inRandomOrder()->first()->id;
       }
        
        return [
            'from_account' => $fromAccount,
            'to_account' => $toAccount,
            'amount' => $this->faker->numberBetween(0, 10000),
            'transaction_id' => Transaction::factory(),
        ];
    }
}
