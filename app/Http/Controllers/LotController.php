<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Lot;
use App\Http\Requests\StoreLotRequest;
use App\Http\Requests\UpdateLotRequest;

use EloquentBuilder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;
use Spatie\Tags\Tag;

class LotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $categories = Category::isRoot()->with(['descendants' => function($query) {
            $query->withCount(['lots' => function($query) {
                $query->active();
            }]);
        }])->withCount(['lots' => function($query) {
            $query->active();
        }])->forShop()->get();

        $tags = Tag::getWithType(Lot::class);

        $breadcrumbs = [
            [
                'link' => route('home'),
                'title' => 'Главная',
            ],
            [
                'link' => route('lots.index'),
                'title' => 'Магазин',
            ],
        ];

        if ($request->has('category') && $request->category) {
            $category_ancestors = Category::forShop()->where('slug', $request->category)->firstOrFail()->ancestorsAndSelf()->get()->reverse();
            $categories = Category::forShop()->where('slug', $request->category)->first()->descendants()->whereDepth(1)->with(['descendants' => function($query) {
                $query->withCount('lots');
            }])->withCount('lots')->get();

            foreach ($category_ancestors as $category) {
                $breadcrumbs[] = [
                    'link' => route('lots.index', $request->except(['category']) + ['category' => $category->slug]),
                    'title' => $category->name,
                ];
            }
        }

        $filters = EloquentBuilder::to(Lot::active()->with(['category' => function($query) {
            $query->with(['lots' => function($query) {
                $query->without('media')->withAvg('ratings as avg_rating', 'rating');
            }]);
        }])->withAvg('ratings as avg_rating', 'rating')->orderForShop(), $request->except(['q', 'sort', 'direction', 'page']));
        $search_results = Search::add($filters, 'title');

        if ($request->filled('sort')) {
            $search_results->orderBy($request->sort);

            if ($request->filled('direction')) {
                if ($request->direction === 'desc') $search_results->orderByDesc();
                else $search_results->orderByAsc();
            }
        }

        $lots = $search_results->paginate(12)->get($request->filled('q') ? $request->q : '');
//        dd($lots);
        return view('shop')->with([
            'lots' => $lots,
            'categories' => $categories,
            'tags' => $tags,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function create(): View|Factory|Application|RedirectResponse
    {
        if(auth()->user()->cannot('create', Lot::class)) {
            return back()->with('error', __('errors.limits.active_lots'));
        }

        $categories = Category::forShop()->get();
        $tags = Tag::getWithType(Lot::class);

        return view('lots.create', [
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLotRequest $request
     * @return RedirectResponse
     */
    public function store(StoreLotRequest $request)
    {
        auth()->user()->cannot('create', Lot::class) && abort(403);

        $lot = Lot::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'properties' => json_encode($request->properties),
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
        ]);

        if ($request->has('images')) {
            foreach ($request->images as $image) {
                $lot->addMedia($image)->toMediaCollection('images');
            }
        }

        if ($request->has('archive')) {
            $lot->addMedia($request->file('archive'))->preservingOriginal()->toMediaCollection('archive', 'lots');
        }

        if($request->has('tags')) {
            $lot->attachTags($request->tags, Lot::class);
        }

        return redirect()->route('dashboard.lots')->with('success', 'Лот был создан и отправлен на модерацию.');
    }

    /**
     * Display the specified resource.
     *
     * @param Lot $lot
     * @return Application|Factory|View
     */
    public function show(Lot $lot)
    {
        if($lot->status !== Lot::ACTIVE_STATUS) {
            if(!auth()->check() || $lot->user_id !== auth()->id() && !auth()->user()->paid($lot)) abort(404);
        }

        $lot = Lot::withCount('reviews')->withAvg('ratings as avg_rating', 'rating')->findOrFail($lot->id)->load('category');

        $lot->increment('views');

        $breadcrumbs = [
            [
                'link' => route('home'),
                'title' => 'Главная',
            ],
            [
                'link' => route('lots.index'),
                'title' => 'Магазин',
            ],
        ];

        $category_ancestors = Category::forShop()->where('slug', $lot->category->slug)->first()->ancestorsAndSelf()->get()->reverse();

        foreach ($category_ancestors as $category) {
            $breadcrumbs[] = [
                'link' => route('lots.index', ['category' => $category->slug]),
                'title' => $category->name,
            ];
        }

        $breadcrumbs[] = [
            'link' => route('lots.show', $lot->slug),
            'title' => $lot->title,
        ];

        return view('lot')->with([
            'lot' => $lot,
            'premium_lots' => Lot::premium()->limit(10)->get(),
            'same_lots' => Lot::where('category_id', $lot->category_id)->limit(10)->get()->except($lot->id),
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Lot $lot
     * @return Application|Factory|View
     */
    public function edit(Lot $lot)
    {
        $categories = Category::forShop()->get();
        $tags = Tag::getWithType(Lot::class);

        return view('lots.edit', [
            'lot' => $lot->load('category'),
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateLotRequest $request
     * @param Lot $lot
     * @return RedirectResponse
     */
    public function update(UpdateLotRequest $request, Lot $lot)
    {
        $lot->update($request->validated() + ['status' => Lot::MODERATION_STATUS]);

        if ($request->has('images')) {
            $lot->clearMediaCollection('images');

            foreach ($request->images as $image) {
                $lot->addMedia($image)->toMediaCollection('images');
            }
        }

        if ($request->has('archive')) {
            $lot->addMediaFromRequest('archive')->toMediaCollection('archive');
        }

        if($request->has('tags')) {
            $lot->detachTags($lot->tags);
            $lot->attachTags($request->tags, Lot::class);
        }

        return redirect()->route('dashboard.lots')->with('success', __('messages.lots.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lot $lot
     * @return Response
     */
    public function destroy(Lot $lot)
    {
        //
    }

    public function getArchive(Lot $lot) {
        if(auth()->user()->paid($lot) || $lot->user_id === auth()->id()) {
            return $lot->hasMedia('archive') ? $lot->getFirstMedia('archive') : back()->with('error', __('errors.lots.no_archive'));
        }
    }

    public function statistics(Lot $lot) {
        $ordered_lots = Lot::orderForShop();
        $position = $lot->getPosition($ordered_lots);
        $position_premium = $lot->getPositionIfPremium($ordered_lots);

        $lot = $lot->load(['purchases' => function($purchases) {
            $purchases->with(['user', 'lot']);
        }]);

        return view('lots.statistics', [
            'lot' => $lot,
            'lot_position' => $position,
            'lot_position_premium' => $position_premium,
            'total_income' => $lot->purchases->count() * $lot->price,
            'rating' => number_format((float) $lot->avgRating, 2, '.', ''),
        ]);
    }
}
