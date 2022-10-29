<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use EloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;
use Spatie\Tags\Tag;

class OrderApiController extends Controller
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
            'freelancer' => function($orders) use($sortOrder) {
                $sort = function ($order) use($sortOrder) {
                    return $order->freelancer->fullName;
                };

                return $sortOrder === 'ASC' ? $orders->sortBy($sort) : $orders->sortByDesc($sort);
            },
            'offers_count' => function($orders) use($sortOrder) {
                $sort = function ($order) use($sortOrder) {
                    return $order->offers_count;
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

        $orders = Order::withCount('offers');
        $sorted = !array_key_exists($sortBy, $custom_sorts) ? $orders->orderBy($sortBy, $sortOrder) : $orders;
        $filters = EloquentBuilder::to($sorted, Arr::except($filter, ['q']));
        $search_results = Search::add($filters, ['title', 'description']);
        $search_collection = $search_results->get($filter['q'] ?? '');
        $result = !array_key_exists($sortBy, $custom_sorts) ? $search_collection : $custom_sorts[$sortBy]($search_collection);

        return response()->json(OrderResource::collection($result->skip($range[0])->take($range[1] - $range[0]+ 1)))
            ->header("X-Total-Count", $result->count())
            ->header("Access-Control-Expose-Headers", "X-Total-Count");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOrderRequest $request
     * @return JsonResponse
     */
    public function store(StoreOrderRequest $request)
    {
        $order = Order::create($request->validated() + ['user_id' => auth()->id()]);

        if($request->has('tag_names')) {
            $order->syncTags($request->tag_names, Order::class);
        }

        if ($request->has('order_files')) {
            foreach ($request->order_files as $file) {
                if(isset($file['src'])) {
                    $order->addMediaFromBase64($file['src'])->usingFileName($file['title'])->toMediaCollection('order_files');
                }
            }
        }

        return response()->json(new OrderResource($order));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        return response()->json(new OrderResource($order));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOrderRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateOrderRequest $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($request->validated());

        if($request->has('tag_names')) {
            $order->syncTags($request->tag_names, Order::class);
        }

        if ($request->has('order_files') && $request->has('except_order_files')) {
            $order->clearMediaCollectionExcept('order_files', $request->except_order_files);

            foreach ($request->order_files as $file) {
                if(isset($file['src'])) {
                    $order->addMediaFromBase64($file['src'])->usingFileName($file['title'])->toMediaCollection('order_files');
                }
            }
        }

        return response()->json(new OrderResource($order));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json(new OrderResource($order));
    }
}
