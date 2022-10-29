<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        \App\Models\User::factory(8)->create();
//         \App\Models\Category::factory(100)->create();
//         \App\Models\Order::factory(50)->create();
//         \App\Models\Lot::factory(50)->create();


        $this->call([
//            RoleSeeder::class,
            FreelancersSeeder::class,
//            FreelancersCategoriesSeeder::class,
//            LotReviewsSeeder::class,
//            UserCountryCodesSeeder::class,
//            OrderOffersSeeder::class,
        ]);
    }
}
