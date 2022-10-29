<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\Review;
use App\Models\Offer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ReviewPolicy
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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param  \App\Models\Review  $review
     * @return Response|bool
     */
    public function view(User $user, Review $review)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param  integer  $user_id
     * @return bool
     */
    public function create(User $user, $user_id): bool
    {
        $review = Review::forUsers()->where([
            ['user_id', $user->id],
            ['reviewable_type', User::class],
            ['reviewable_id', $user_id],
        ]);

        $completed_order = Order::whereDate(
            'completed_at', '>=', Carbon::now()->subWeek()
        );

        $completed_order->where(function($query) use($user_id, $user) {
            $query
                ->where([
                    ['user_id', $user_id],
                    ['freelancer_id', $user->id],
                ])
                ->orWhere([
                    ['user_id', $user->id],
                    ['freelancer_id', $user_id],
                ]);
        });

        return $completed_order->exists() && !$review->exists();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param  string  $reviewable_id
     * @return Response|bool
     */
     public function createLotReview(User $user, $reviewable_id)
        {return true;
            if($user->isAdmin) return true;

            if(!Review::where([
                ['user_id', $user->id],
                ['reviewable_id', $reviewable_id],
            ])->exists()) {
                $review = Review::findOrFail($reviewable_id);
                return $user->hasPurchased($review);
            }

            return false;
        }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param  \App\Models\Review  $review
     * @return Response|bool
     */
    public function update(User $user, Review $review)
    {
        return $review->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param  \App\Models\Review  $review
     * @return Response|bool
     */
    public function delete(User $user, Review $review)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param  \App\Models\Review  $review
     * @return Response|bool
     */
    public function restore(User $user, Review $review)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param  \App\Models\Review  $review
     * @return Response|bool
     */
    public function forceDelete(User $user, Review $review)
    {
        return true;
    }
}
