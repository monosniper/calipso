<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePortfolioRequest;
use App\Models\Portfolio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PortfolioController extends Controller
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
     * @param StorePortfolioRequest $request
     * @return RedirectResponse
     */
    public function store(StorePortfolioRequest $request): RedirectResponse
    {
        $portfolio = new Portfolio;

        $portfolio->title = $request->title;
        $portfolio->link = $request->link;
        $portfolio->description = $request->description;
        $portfolio->tag = $request->tag;
        $portfolio->user_id = auth()->id();

        $portfolio->save();

        $portfolio
            ->addMedia($request->preview)
            ->toMediaCollection('preview');

        return back()->with('success', 'Портфолио создано успешно');
    }

    /**
     * Display the specified resource.
     *
     * @param Portfolio $portfolio
     * @return void
     */
    public function show(Portfolio $portfolio)
    {
        $portfolio->increment('views');
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
     * @param Request $request
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
     * @param  Portfolio  $portfolio
     * @return RedirectResponse
     */
    public function destroy(Portfolio $portfolio): RedirectResponse
    {
        $portfolio->delete();

        return back()->with('success', __('messages.portfolios.deleted'));
    }
}
