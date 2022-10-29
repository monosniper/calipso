<?php

namespace App\Observers;

use App\Models\Lot;
use App\Models\Review;
use App\Models\User;
use JetBrains\PhpStorm\NoReturn;

class ReviewObserver
{
    /**
     * Handle the Review "created" event.
     *
     * @param Review $review
     * @return void
     */
    public function created(Review $review)
    {

    }

    /**
     * Handle the Review "updated" event.
     *
     * @param Review $review
     * @return void
     */
    public function updated(Review $review)
    {
        //
    }

    /**
     * Handle the Review "deleted" event.
     *
     * @param Review $review
     * @return void
     */
    public function deleted(Review $review)
    {
        //
    }

    /**
     * Handle the Review "restored" event.
     *
     * @param Review $review
     * @return void
     */
    public function restored(Review $review)
    {
        //
    }

    /**
     * Handle the Review "force deleted" event.
     *
     * @param Review $review
     * @return void
     */
    public function forceDeleted(Review $review)
    {
        //
    }
}
