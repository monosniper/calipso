<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'days' =>  $this->faker->numberBetween(1, 15),
            'content' => $this->faker->text(500),
            'price' =>  $this->faker->numberBetween(100, 1000),
            'user_id' => User::pluck('id')->random(),
        ];
    }
}
