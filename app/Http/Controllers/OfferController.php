<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOfferRequest;
use App\Models\Offer;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOfferRequest $request
     * @return RedirectResponse
     */
    public function store(StoreOfferRequest $request)
    {
        $user = auth()->user();

        if($user->id === $request->order_id) {
            return back()->with('error', 'Вы являетесь владельцем заказа.');
        }

        if($user->hasOfferOf($request->order_id)) {
            return back()->with('error', 'Вы уже делали отклик на этот заказ');
        }

        if($user->isOffersLimitSpent()) {
            return back()->with('error', 'Вы исчерпали лимит откликов (максимум '.config('calipso.limits.offers').' в день).');
        }

        $offer = new Offer();

        $offer->user_id = $user->id;
        $offer->order_id = $request->order_id;
        $offer->content = $request->input('content');
        $offer->days = $request->days;
        $offer->price = $request->price;
        $offer->isSafe = $request->filled('isSafe');

        $offer->save();

        return back()->with('success', 'Ваш отклик добавлен успешно.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
