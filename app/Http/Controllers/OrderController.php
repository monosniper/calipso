<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Offer;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Safe;
use Carbon\Carbon;
use EloquentBuilder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;
use Spatie\Tags\Tag;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $categories = Category::isRoot()->forFreelance()->withCount('orders')->get();
        $tags = Tag::getWithType(Order::class);

        $breadcrumbs = [
            [
                'link' => route('home'),
                'title' => 'Главная',
            ],
            [
                'link' => route('orders.index'),
                'title' => 'Фриланс',
            ],
        ];

        if($request->has('category')) {
            $category_ancestors = Category::forFreelance()->where('slug', $request->category)->first()->ancestorsAndSelf()->get()->reverse();
            $categories = Category::forFreelance()->where('slug', $request->category)->first()->descendants()->whereDepth(1)->withCount('orders')->get();

            foreach ($category_ancestors as $category) {
                $breadcrumbs[] = [
                    'link' => route('orders.index', $request->except(['category']) + ['category' => $category->slug]),
                    'title' => $category->name,
                ];
            }
        }

        $filters = EloquentBuilder::to(Order::active()->withCount('offers')->with('user'), $request->except(['q', 'sort', 'direction', 'page']));
        $search_results = Search::add($filters, 'title')->orderBy('created_at')->orderByDesc();

        if($request->filled('sort')) {
            $search_results->orderBy($request->sort);

            if($request->filled('direction')) {
                if($request->direction === 'desc') $search_results->orderByDesc();
                else $search_results->orderByAsc();
            }
        }

        $orders = $search_results->paginate(12)->get($request->filled('q') ? $request->q : '');

        return view('freelance')->with([
            'orders' => $orders,
            'categories' => $categories,
            'tags' => $tags,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        if(auth()->user()->cannot('create', Order::class)) {
            return back()->with('error', __('errors.limits.active_orders'));
        }

        $categories = Category::forFreelance()->get();
        $tags = Tag::getWithType(Order::class);

        return view('orders.create', [
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOrderRequest $request
     * @return RedirectResponse
     */
    public function store(StoreOrderRequest $request)
    {
        auth()->user()->cannot('create', Order::class) && abort(403);

        $order = Order::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'days' => $request->days,
            'isSafe' => $request->filled('isSafe'),
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
        ]);

        if($request->has('tags')) {
            $order->attachTags($request->tags, Order::class);
        }

        if ($request->has('files')) {
            foreach ($request->file('files', []) as $key => $file) {
                $order->addMedia($file)->preservingOriginal()->toMediaCollection('files');
            }
        }

        return redirect()->route('dashboard.orders')->with('success', 'Ваш заказ был создан успешно.');
    }

    /**
     * Display the specified resource.
     *
     * @param Order $order
     * @return View
     */
    public function show(Order $order): View
    {
        $order->increment('views');

        return view('order')->with([
            'order' => $order->load([
                'user' => function(BelongsTo $user) {
                    $user->withCount([
                        'orders as completed_orders_count' => function(Builder $query) {
                            $query->completed();
                        }, 'orders as active_orders_count' => function(Builder $query) {
                            $query->active();
                        }, 'reviews as positive_reviews_count' => function(Builder $query) {
                            $query->positive();
                        }, 'reviews as negative_reviews_count' => function(Builder $query) {
                            $query->negative();
                        }
                    ]);
                },
                'offers' => function(HasMany $offers) {
                    $offers->with([
                        'user' => function(BelongsTo $user) {
                            $user->withCount([
                                'reviews as positive_reviews_count' => function(Builder $query) {
                                    $query->positive();
                                }, 'reviews as negative_reviews_count' => function(Builder $query) {
                                    $query->negative();
                                }
                            ]);
                        }
                    ]);
                }
            ])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Order $order
     * @return View
     */
    public function edit(Order $order): View
    {
        auth()->user()->cannot('update', $order) && abort(403);

        $categories = Category::forFreelance()->get();
        $tags = Tag::getWithType(Order::class);

        return view('orders.edit', [
            'order' => $order->load('category'),
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateOrderRequest $request
     * @param Order $order
     * @return RedirectResponse
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());

        if ($request->has('files')) {
            $order->clearMediaCollection('files');
            foreach ($request->file('files', []) as $key => $file) {
                $order->addMedia($file)->preservingOriginal()->toMediaCollection('files');
            }
        }

        if($request->has('tags')) {
            $order->detachTags($order->tags);
            $order->attachTags($request->tags, Order::class);
        }

        return redirect()->route('dashboard.orders')->with('success', __('messages.orders.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function chooseOffer(Order $order, Offer $offer) {
        if(!auth()->id() === $order->user_id) abort(403);

        if($order->offer()) abort(400, __('errors.orders.already_has_offer'));

        $order->freelancer_id = $offer->user_id;
        $order->save();

        if($offer->isSafe) {
            $order->safe->setStatus(Safe::AGREEMENT_STATUS);

            return redirect()->route('freelance.safe', $order->id);
        } else {
            $order->setStatus(Order::WORK_STATUS);

            return redirect()->route('dashboard.work');
        }
    }

    public function closeOrder(Order $order) {
        if(auth()->id() !== $order->user_id) abort(403);

        if($order->safe){
            if(!$order->safe->result_link) abort(401, __('errors.safe.cant_close_without_result'));

            $order->safe->setStatus(Safe::REVIEWS_STATUS);
        }

        $order->setStatus(Safe::REVIEWS_STATUS);
        $order->completed_at = Carbon::now();
        $order->save();

        $order->freelancer->deposit($order->price * 100, [
            'description' => __('safe.pay_description'),
        ]);

        return back()->with('success', __('order.completed'));
    }

    public function agree(Request $request, Order $order) {
        if($order->freelancer_id !== auth()->id()) abort(403);

        $order->safe->setStatus(Safe::RESERVATION_STATUS);

        return back()->with('success', __('safe.success.agreement'));
    }

    public function reserve(Order $order) {
        $user = auth()->user();

        if($order->user_id !== $user->id) abort(403);

        if(!$order->isSafe || $order->status !== Safe::RESERVATION_STATUS) abort(401);

        try {
            $user->withdraw($order->price * 100, ['description' => __('safe.safe_pay', ['id' => $order->id])]);
        } catch (\Exception $exception) {
            return back()->with('error' , __('errors.not_enough_money'));
        }

        $order->safe->setStatus(Safe::WORK_STATUS);

        return back()->with('success', __('safe.success.reservation'));
    }
}
