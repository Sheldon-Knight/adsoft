<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Leave>
 */
class LeaveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        $from = Carbon::today()->startOfYear()->addDays(rand(0, 365));
        $to = $from->addDays(rand(1, 10));

        return [
            'user_id' => $user->id,
            'department_id' => $user->department_id ?? null,
            'from' => $from,
            'to' => $to,
            'type' => $this->faker->randomElement(['Annual', 'Sick', 'Family', 'Maternity', 'Unpaid', 'Study']),
            'revisioned_by' => User::find(1)->id,
            'revisioned_on' => $from->addDay(),
            'status' => rand(0, 1),
            'user_notes' => $this->faker->sentence(),
            'revisioned_notes' => $this->faker->sentence(),
        ];
    }
}
