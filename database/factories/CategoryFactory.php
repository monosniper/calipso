<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $parent_id = null;
        $categories = Category::isLeaf()->forFreelance()->pluck('id');

        if($categories->count()) $parent_id = $categories->random();

        return [
            'name' => $this->faker->word(),
            'parent_id' => $parent_id,
            'for' => 'freelance',
        ];
    }
}
