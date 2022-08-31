<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'postal_address' => $this->faker->address(),
            'physical_address' => $this->faker->address(),
            'vat_number' => $this->faker->numerify('##########'),
            'client_name' => $this->faker->firstname(),
            'client_surname' => $this->faker->lastname(),
            'tel_num' => $this->faker->phoneNumber,
            'cell_num' => $this->faker->phoneNumber,
            'fax_num' => $this->faker->phoneNumber,
            'contact_person' => $this->faker->firstname(),
            'reg_type' => $this->faker->randomElement(['Business', 'Personal']),
            'reg_number' => $this->faker->numerify('##########'),
            'account_name' => $this->faker->word,
            'account_number' => $this->faker->numerify('##########'),
            'account_type' => $this->faker->randomElement([
                'Savings Account',
                'Cheque Account',
                'Trust Account',
            ]),
            'branch_code' => $this->faker->numberBetween(1000, 6000),
            'bank_name' => $this->faker->randomElement([
                'FNB Bank',
                'Standard Bank',
                'Absa Bank',
                'Capitec Bank',
                'Discovery Bank',
                'U Bank',
                'RMB Bank',
                'Sasfin Bank',
                'Investec Bank',
                'Grindrod Bank',
                'Bidvest Bank',
                'Imperial Bank',
                'Grindrod Bank', ]),

            'branch_name' => $this->faker->randomElement(['Florida', 'Roodekraans', 'Mosselbay', 'George']),
            'email' => $this->faker->safeEmail(),
            'client_status' => $this->faker->randomElement(['Active', 'Inactive']),

        ];
    }
}
