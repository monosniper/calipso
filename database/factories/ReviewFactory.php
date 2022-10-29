<?php

namespace Database\Factories;

use App\Models\Lot;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->text(500),
            'rating' => $this->faker->numberBetween(1, 5),
            'user_id' => User::pluck('id')->random(),
            'reviewable_type' => Lot::class,
        ];
    }
}
