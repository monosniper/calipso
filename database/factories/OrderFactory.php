<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $parent_id = null;
        $categories = Category::where('for', 'freelance')->pluck('id');

        if($categories->count()) $parent_id = $categories->random();

        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->text(1000),
            'price' => $this->faker->numberBetween(10, 1000),
            'status' => Order::ACTIVE_STATUS,
            'views' => $this->faker->randomNumber(4),
            'days' => $this->faker->randomNumber(2),
            'user_id' => User::pluck('id')->random(),
            'isUrgent' => $this->faker->boolean(),
            'category_id' => $parent_id,
        ];
    }
}
