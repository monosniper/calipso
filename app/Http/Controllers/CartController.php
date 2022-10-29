<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\LotPurchase;
use Illuminate\Http\Request;
use Cart;

class CartController extends Controller
{
    /**
     * Pay for all items in a cart.
     *
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pay()
    {
        Cart::instance('basket')->restore(auth()->id());
        $items = Cart::content();
        $user = auth()->user();

        foreach ($items as $item) {
            $lot = Lot::findOrFail($item->id);

            if(!$user->safePay($lot)) {
                return back()->with('error', __('errors.not_enough_money'));
            }

            Cart::instance('basket')->remove($item->rowId);
            LotPurchase::create([
                'user_id' => $user->id,
                'lot_id' => $lot->id,
            ]);
        }

        Cart::store(auth()->id());

        return back()->with('success', __('main.success_pay_cart'));
    }
}
