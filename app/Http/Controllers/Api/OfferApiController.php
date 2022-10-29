<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use EloquentBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class OfferApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $filter = (array) json_decode($request->filter);
        $sort = json_decode($request->sort);
        $range = json_decode($request->range);

        $sortBy = $sort[0];
        $sortOrder = $sort[1];

        $custom_sorts = [
            'user' => function($orders) use($sortOrder) {
                $sort = function ($order) use($sortOrder) {
                    return $order->user->fullName;
                };

                return $sortOrder === 'ASC' ? $orders->sortBy($sort) : $orders->sortByDesc($sort);
            },
            'freelancer' => function($orders) use($sortOrder) {
                $sort = function ($order) use($sortOrder) {
                    return $order->freelancer->fullName;
                };

                return $sortOrder === 'ASC' ? $orders->sortBy($sort) : $orders->sortByDesc($sort);
            },
            'category' => function($orders) use($sortOrder) {
                $sort = function ($order) use($sortOrder) {
                    return $order->category->name;
                };

                return $sortOrder === 'ASC' ? $orders->sortBy($sort) : $orders->sortByDesc($sort);
            },
        ];

        $offers = Offer::with(['order', 'user']);
        $sorted = !array_key_exists($sortBy, $custom_sorts) ? $offers->orderBy($sortBy, $sortOrder) : $offers;
        $filters = EloquentBuilder::to($sorted, Arr::except($filter, ['q']));
        $search_results = Search::add($filters, ['content']);
        $search_collection = $search_results->get($filter['q'] ?? '');
        $result = !array_key_exists($sortBy, $custom_sorts) ? $search_collection : $custom_sorts[$sortBy]($search_collection);

        return response()->json(OfferResource::collection($result->skip($range[0])->take($range[1] - $range[0]+ 1)))
            ->header("X-Total-Count", $result->count())
            ->header("Access-Control-Expose-Headers", "X-Total-Count");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Offer $offer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Offer $offer)
    {
        //
    }
}
