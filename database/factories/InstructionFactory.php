<?php

namespace Database\Factories;

use App\Models\Instruction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstructionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Instruction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'instruction' => $this->faker->paragraph,
            'due_date' => $this->faker->dateTimeBetween('-1 years', '+1 years'),
            'date_completed' => $this->faker->dateTimeBetween('-1 years', '+1 years'),
            'status' => $this->faker->randomElement([1, 0]),
            'created_by' => User::factory(),
            'assigned_to' => User::factory(),
        ];
    }
}
