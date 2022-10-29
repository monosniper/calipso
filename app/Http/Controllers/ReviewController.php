<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Lot;
use App\Models\User;
use App\Http\Requests\StoreReviewRequest;

class ReviewController extends Controller
{

    public function __construct() {
//         $this->authorizeResource(Review::class, 'review');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreReviewRequest $request
     * @return RedirectResponse
     */
    public function store(StoreReviewRequest $request): RedirectResponse
    {
        !auth()->check() && abort(401, 'Для начала нужно войти в аккаунт');

        $user = auth()->user();

        if(!$user->isAdmin) {
            if(Review::where([
                ['user_id', $user->id],
                ['reviewable_type', $request->reviewable_type],
                ['reviewable_id', $request->reviewable_id],
            ])->exists()) {
                abort(403, 'Вы уже написали отзыв');
            }

            if($request->reviewable_type === User::class) {
                $user->cannot('create', [Review::class, $request->reviewable_id]) && abort(403);
            } else if($request->reviewable_type === Lot::class) {
                if(!$user->hasPurchasedLot($request->reviewable_id)) {
                    abort(403, 'Отзыв можно написать только после покупки');
                }
            }
        }

        $reviewable_types = [
            Lot::class => Lot::findOrFail($request->reviewable_id),
            User::class => User::findOrFail($request->reviewable_id),
        ];

        $reviewable = $reviewable_types[$request->reviewable_type];

        $review = new Review;

        $review->title = $request->title;
        $review->content = $request->input('content');
        $review->rating = $request->rating;
        $review->user_id = $user->id;

        $review->reviewable()->associate($reviewable);

        $review->save();

        if($request->reviewable_type === User::class) {
            if($review->isNegative()) {
                $reviewable->decreaseRating(config('calipso.freelance.rating.review'));
            } else {
                $reviewable->addRating(config('calipso.freelance.rating.review'));
            }
        } else if($review->reviewable_type === Lot::class) {
            $reviewable->rate($request->rating, $user->id);
        }

        return back()->with('success', 'Отзыв добавлен успешно.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function like(Review $review) {
        $review->like();
    }

    public function unlike(Review $review) {
        $review->unlike();
    }
}
