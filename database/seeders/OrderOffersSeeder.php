<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderOffersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Order::all() as $order) {
            Offer::factory(15)->create([
                'order_id' => $order->id,
            ]);
        }
    }
}
