<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Resources\TagResource;
use App\Models\Order;
use EloquentBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;
use Spatie\Tags\Tag;

class TagApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $filter = (array) json_decode($request->filter);
        if($request->has('sort')) {
            $sort = json_decode($request->sort);
        } else {
            $sort = ['created_at', 'desc'];
        }

        if($request->has('range')) {
            $range = json_decode($request->range);
        } else {
            $range = false;
        }

        $sortBy = $sort[0];
        $sortOrder = $sort[1];

        $types = [
            'orders' => Order::class,
        ];

        if(isset($filter['id'])) {
            if(is_array($filter['id'])) {
                if(!empty($filter['id'])) {
                    if(is_object($filter['id'][0])) {
                        $tags = Tag::whereIn('id', array_column($filter['id'], 'id'))->get();
                    } else {
                        $tags = Tag::whereIn('id', $filter['id'])->get();
                    }
                } else {
                    $tags = Tag::getWithType($types[$filter['for']]);
                }
            }
        } else {
            $tags = Tag::getWithType($types[$filter['for']]);
        }

        return response()->json(TagResource::collection($tags))
            ->header("X-Total-Count", $tags->count())
            ->header("Access-Control-Expose-Headers", "X-Total-Count");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  Tag  $tag
     * @return JsonResponse
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Models\Tag  $tag
     * @return JsonResponse
     */
    public function update(Request $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return JsonResponse
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
