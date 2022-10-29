<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use EloquentBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class ReviewApiController extends Controller
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

        $filters = EloquentBuilder::to(Review::orderBy($sort[0], $sort[1]), Arr::except($filter, ['q']));
        $search_results = Search::add($filters, ['title', 'content']);
        $reviews = $search_results->get($filter['q'] ?? '');

        return response()->json(ReviewResource::collection($reviews->skip($range[0])->take($range[1] - $range[0]+ 1)))
            ->header("X-Total-Count", $reviews->count())
            ->header("Access-Control-Expose-Headers", "X-Total-Count");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $review = Review::create($request->validated());
        return response()->json(new ReviewResource($review));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $review = Review::findOrFail($id);
        return response()->json(new ReviewResource($review));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $review->update($request->validated());
        return response()->json(new ReviewResource($review));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        return response()->json(new ReviewResource($review));
    }
}
