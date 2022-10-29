<?php

namespace Database\Factories;

use App\Models\Lot;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\User;

class LotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $parent_id = null;
        $categories = Category::forShop()->pluck('id');

        if($categories->count()) $parent_id = $categories->random();

        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->text(1000),
            'price' => $this->faker->numberBetween(10, 1000),
            'isPremium' => $this->faker->boolean(),
            'status' => Lot::ACTIVE_STATUS,
            'views' => $this->faker->randomNumber(4),
            'user_id' => User::pluck('id')->random(),
            'category_id' => $parent_id,
        ];
    }
}
