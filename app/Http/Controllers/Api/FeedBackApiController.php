<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Requests\UpdateFeedBackRequest;
use App\Http\Resources\FeedBackResource;
use App\Mail\FeedbackAnswer;
use App\Models\Feedback;
use EloquentBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class FeedBackApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filter = (array) json_decode($request->filter);
        $sort = json_decode($request->sort);
        $range = json_decode($request->range);

        $filters = EloquentBuilder::to(Feedback::orderBy($sort[0], $sort[1]), Arr::except($filter, ['q']));
        $search_results = Search::add($filters, ['theme', 'content', 'email']);
        $feedBacks = $search_results->get($filter['q'] ?? '');

        return response()->json(FeedBackResource::collection($feedBacks->skip($range[0])->take($range[1] - $range[0]+ 1)))
            ->header("X-Total-Count", $feedBacks->count())
            ->header("Access-Control-Expose-Headers", "X-Total-Count");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFeedBackRequest $request
     * @return JsonResponse
     */
    public function store(StoreFeedBackRequest $request): JsonResponse
    {
        $feedBack = Feedback::create($request->validated());
        return response()->json($feedBack);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $feedBack = Feedback::findOrFail($id);
        return response()->json($feedBack);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateFeedBackRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateFeedBackRequest $request, int $id): JsonResponse
    {
        $feedBack = Feedback::findOrFail($id);
        $feedBack->update($request->validated());
        return response()->json($feedBack);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $feedBack = Feedback::findOrFail($id);
        $feedBack->delete();
        return response()->json($feedBack);
    }

    public function answer(Feedback $feedback) {
        Mail::to($feedback->email)->send(new FeedbackAnswer($feedback));

        return response()->json($feedback);
    }
}
