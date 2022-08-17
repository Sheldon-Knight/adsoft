<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'account_number' => $this->faker->numberBetween(100000000, 999999999),
            'bank_name' => $this->faker->company(),
            'branch' => $this->faker->name,
            'branch_code' => $this->faker->numberBetween(1000, 9999),
            'balance' => $this->faker->numberBetween(0, 10000),
        ];        
    }
}
