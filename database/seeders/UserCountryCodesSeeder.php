<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use \Faker\Generator;
use \Faker\Factory;

class UserCountryCodesSeeder extends Seeder
{
    protected Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(User::all() as $user) {
            $user->country_code = $this->faker->countryCode();
            $user->save();
        }
    }
}
