<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->safeEmail(),
            'theme' => $this->faker->sentence,
            'content' => $this->faker->text,
            'answer' => $this->faker->text,
        ];
    }
}
