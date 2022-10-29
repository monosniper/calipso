<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Safe;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        if(str_contains($order->description, 'Powered by')) {
            $order->description = mb_substr($order->description, 0 , -229);
            $order->saveQuietly();
        }

        if($order->isSafe) {
            Safe::create(['order_id' => $order->id]);
        }
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        if(str_contains($order->description, 'Powered by')) {
            $order->description = mb_substr($order->description, 0 , -229);
            $order->saveQuietly();
        }

        if($order->isSafe && !$order->safe) {
            Safe::create(['order_id' => $order->id]);
        }
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
