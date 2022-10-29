<?php

namespace App\Observers;

use App\Models\Lot;

class LotObserver
{
    /**
     * Handle the Lot "created" event.
     *
     * @param  \App\Models\Lot  $lot
     * @return void
     */
    public function created(Lot $lot)
    {
        if(str_contains($lot->description, 'Powered by')) {
            $lot->description = mb_substr($lot->description, 0 , -229);
            $lot->saveQuietly();
        }
    }

    /**
     * Handle the Lot "updated" event.
     *
     * @param  \App\Models\Lot  $lot
     * @return void
     */
    public function updated(Lot $lot)
    {
        if(str_contains($lot->description, 'Powered by')) {
            $lot->description = mb_substr($lot->description, 0 , -229);
        }

        $lot->saveQuietly();
    }

    /**
     * Handle the Lot "deleted" event.
     *
     * @param  \App\Models\Lot  $lot
     * @return void
     */
    public function deleted(Lot $lot)
    {
        //
    }

    /**
     * Handle the Lot "restored" event.
     *
     * @param  \App\Models\Lot  $lot
     * @return void
     */
    public function restored(Lot $lot)
    {
        //
    }

    /**
     * Handle the Lot "force deleted" event.
     *
     * @param  \App\Models\Lot  $lot
     * @return void
     */
    public function forceDeleted(Lot $lot)
    {
        //
    }
}
