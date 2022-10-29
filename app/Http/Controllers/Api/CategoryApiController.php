<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\User;
use EloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Arr;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class CategoryApiController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->authorizeResource(Category::class, 'category');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($for, Request $request)
    {
//        $this->authorize('viewAny', Category::class);
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
            'shop' => Category::forShop(),
            'freelance' => Category::forFreelance(),
        ];

        $custom_sorts = [
            'freelancers_count',
            'items_count',
        ];

        $forCategories = $types[$for]->withCount(['freelancers','lots','orders']);
        $sorted = !in_array($sortBy, $custom_sorts) ? $forCategories->orderBy($sortBy, $sortOrder) : $forCategories;
        $filters = EloquentBuilder::to($sorted, Arr::except($filter, ['q']));
        $search_results = Search::add($filters, ['slug', 'name']);
        $categories = $search_results->get($filter['q'] ?? '');

        if($range) {
            $response = CategoryResource::collection($categories->skip($range[0])->take($range[1] - $range[0] + 1));
        } else {
            $response = CategoryResource::collection($categories);
        }

        return response()->json($response)
            ->header("X-Total-Count", $categories->count())
            ->header("Access-Control-Expose-Headers", "X-Total-Count");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($for, StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated() + ['for' => $for]);
        return response()->json(new CategoryResource($category));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($for, $id)
    {
        $category = Category::findOrFail($id);
        return response()->json(new CategoryResource($category));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($for, UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());
        return response()->json(new CategoryResource($category));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($for, $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(new CategoryResource($category));
    }
}
