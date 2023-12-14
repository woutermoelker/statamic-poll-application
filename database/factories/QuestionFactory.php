<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $now = Carbon::now();
        return [
            "question" => $this->faker->realText(20),
            "type" => $this->faker->randomElement(['radio', 'checkbox']),
            "start_date" => $now,
            "end_date" => $this->faker->dateTimeBetween($now, '+1 month'),
        ];
    }
}
