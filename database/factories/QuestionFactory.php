<?php

namespace Database\Factories;

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
        return [
            "question" => $this->faker->realText(20),
            "type" => $this->faker->randomElement(['radio', 'checkbox']),
            "start_date" => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            "end_date" => $this->faker->dateTimeBetween('-1 year', '+1 year'),
        ];
    }
}
