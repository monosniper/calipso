<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lot;

class LotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//         // Let's truncate our existing records to start from scratch.
//         Lot::truncate();
//
//         $faker = \Faker\Factory::create();
//
//         // And now, let's create a few articles in our database:
//         for ($i = 0; $i < 50; $i++) {
//             Lot::create([
//                 'title' => $faker->sentence,
//                 'description' => $faker->paragraph,
//                 'price' => $faker->numberBetween($min = 10, $max = 1000),
//                 'category_id' => $faker->numberBetween($min = 10, $max = 1000),
//             ]);
//         }
    }
}
