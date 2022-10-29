<?php

namespace Database\Seeders;

use App\Models\Lot;
use App\Models\Review;
use Illuminate\Database\Seeder;

class LotReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Lot::all() as $lot) {
            Review::factory(15)->create([
                'reviewable_id' => $lot->id,
            ]);
        }
    }
}
