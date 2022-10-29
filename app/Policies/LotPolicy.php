<?php

namespace App\Policies;

use App\Models\Lot;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LotPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Lot $lot
     * @return Response|bool
     */
    public function view(User $user, Lot $lot)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user): Response|bool
    {
        return $user->lots()->active()->count() <= config('calipso.limits.active_lots');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Lot $lot
     * @return Response|bool
     */
    public function update(User $user, Lot $lot)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Lot $lot
     * @return Response|bool
     */
    public function delete(User $user, Lot $lot)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Lot $lot
     * @return Response|bool
     */
    public function restore(User $user, Lot $lot)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Lot $lot
     * @return Response|bool
     */
    public function forceDelete(User $user, Lot $lot)
    {
        //
    }
}
