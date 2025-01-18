<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    public function definition(): array
    {
        $dates = [now()->addDay(), now()->addDays(10), now()->subDay(), now()->subDays(10)];

        return [
            'front' => $this->faker->sentence(rand(2, 10)),
            'back' => $this->faker->sentence(rand(2, 10)),
            'deck_id' => rand(1, 10),
            'when_ask' => $dates[array_rand($dates)]
        ];
    }
}
