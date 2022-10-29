<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use EloquentBuilder;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Arr;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class UserApiController extends Controller
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

        $filters = EloquentBuilder::to(User::orderBy($sort[0], $sort[1]), Arr::except($filter, ['q']));
        $search_results = Search::add($filters, ['username', 'first_name', 'last_name', 'email']);
        $users = $search_results->get($filter['q'] ?? '');

        return response()->json(UserResource::collection($users->skip($range[0])->take($range[1] - $range[0]+ 1)))
            ->header("X-Total-Count", $users->count())
            ->header("Access-Control-Expose-Headers", "X-Total-Count");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());

        if(!empty($request->roles)) {
            foreach ($request->roles as $role_name) {
                $role = Role::where('name', $role_name);

                if($role->exists()) {
                    $user->roles()->attach($role->first()->id);
                }
            }
        }

        return response()->json(new UserResource($user));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());

        if($request->has('roles')) {
            $user->roles()->detach();

            foreach ($request->roles as $role_name) {
                $role = Role::where('name', $role_name)->firstOrFail();
                $user->roles()->save($role);
            }
        }

        return response()->json(new UserResource($user));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(new UserResource($user));
    }
}
