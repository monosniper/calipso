<?php

namespace App\Observers;

use App\Models\User;
use Stevebauman\Location\Facades\Location;

class UserObserver
{

    /**
     * Handle the User "created" event.
     *
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        if(!$user->username) {
            $username = substr($user->email, 0, strpos($user->email, '@'));
            $users_with_same_username = User::where('username', $username);

            if($users_with_same_username->exists()) {
                $count = $users_with_same_username->count();
                $username = "$username-$count";
            }

            $user->username = $username;
        }

        if ($position = Location::get(request()->ip())) {
            $user->location = $position->countryName . ' ' . $position->cityName;
            $user->country_code = $position->countryCode;
        }

        $user->saveQuietly();
    }

    /**
     * Handle the User "updated" event.
     *
     * @param User $user
     * @return void
     */
    public function updated(User $user)
    {
        if(
            $user->first_name &&
            $user->last_name &&
            $user->resume &&
            $user->location
        ) $user->addRating(config('calipso.freelance.rating.profile_fill'), 'profile_fill');

        if(str_contains($user->resume, 'Powered by')) {
            $user->resume = mb_substr($user->resume, 0 , -229);
        }

        if($user->location === '' || !$user->location) {
            if ($position = Location::get(request()->ip())) {
                $user->location = $position->countryName . ' ' . $position->cityName;
                $user->country_code = $position->countryCode;
            }
        }

        $user->saveQuietly();
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param User $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
