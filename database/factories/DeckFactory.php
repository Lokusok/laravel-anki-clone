<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeckFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'user_id' => 1,
        ];
    }
}
