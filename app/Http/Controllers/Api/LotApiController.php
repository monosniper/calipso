<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLotRequest;
use App\Http\Requests\UpdateLotRequest;
use EloquentBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\LotResource;
use App\Models\Lot;
use Illuminate\Support\Arr;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class LotApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
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
            'category' => function($orders) use($sortOrder) {
                $sort = function ($order) use($sortOrder) {
                    return $order->category->name;
                };

                return $sortOrder === 'ASC' ? $orders->sortBy($sort) : $orders->sortByDesc($sort);
            },
        ];

        $lots = Lot::query();
        $sorted = !array_key_exists($sortBy, $custom_sorts) ? $lots->orderBy($sortBy, $sortOrder) : $lots;
        $filters = EloquentBuilder::to($sorted, Arr::except($filter, ['q']));
        $search_results = Search::add($filters, ['title', 'description']);
        $search_collection = $search_results->get($filter['q'] ?? '');
        $result = !array_key_exists($sortBy, $custom_sorts) ? $search_collection : $custom_sorts[$sortBy]($search_collection);

        return response()->json(LotResource::collection($result->skip($range[0])->take($range[1] - $range[0]+ 1)))
            ->header("X-Total-Count", $result->count())
            ->header("Access-Control-Expose-Headers", "X-Total-Count");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLotRequest $request
     * @return JsonResponse
     */
    public function store(StoreLotRequest $request)
    {
        $lot = Lot::create($request->validated() + ['properties' => json_encode($request->properties)]);

        if($request->has('tag_names')) {
            $lot->syncTags($request->tag_names, Lot::class);
        }

        if ($request->has('images')) {
            foreach ($request->images as $file) {
                if(isset($file['src'])) {
                    $lot->addMediaFromBase64($file['src'])->usingFileName($file['title'])->toMediaCollection('images');
                }
            }
        }

        if ($request->has('archive')) {
            foreach ($request->archive as $file) {
                if(isset($file['src'])) {
                    $lot->addMediaFromBase64($file['src'])->usingFileName($file['title'])->toMediaCollection('archive');
                }
            }
        }

        return response()->json(new LotResource($lot));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $lot = Lot::findOrFail($id);
        return response()->json(new LotResource($lot));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(UpdateLotRequest $request, $id)
    {
        $lot = Lot::findOrFail($id);
        $lot->updateQuietly($request->validated());

        if($request->has('tag_names')) {
            $lot->syncTags($request->tag_names, Lot::class);
        }

        if ($request->has('images') && $request->has('except_images')) {
            $lot->clearMediaCollectionExcept('images', $request->except_images);

            foreach ($request->images as $file) {
                if(isset($file['src'])) {
                    $lot->addMediaFromBase64($file['src'])->usingFileName($file['title'])->toMediaCollection('images');
                }
            }
        }

        if ($request->has('archive') && $request->has('except_archive')) {
            foreach ($request->archive as $file) {
                if(isset($file['src'])) {
                    $lot->addMediaFromBase64($file['src'])->usingFileName($file['title'])->toMediaCollection('archive');
                }
            }
        }

        return response()->json(new LotResource($lot));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $lot = Lot::findOrFail($id);
        $lot->delete();
        return response()->json(new LotResource($lot));
    }
}
