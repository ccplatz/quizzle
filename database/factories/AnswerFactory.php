<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Answer>
 */
class AnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'text' => $this->faker->sentence,
            'correct' => $this->faker->boolean,
            'identifier' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J']),
        ];
    }

    /**
     * Indicate that the answer is correct.
     */
    public function correct(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'correct' => true,
            ];
        });
    }
}