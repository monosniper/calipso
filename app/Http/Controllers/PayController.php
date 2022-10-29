<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Signature;
use App\Models\User;
use Illuminate\Http\Request;

class PayController extends Controller
{
    public function payCallback(Request $request) {
        Signature::merchant(config('fondy.merchant_id'));
        Signature::password(config('fondy.password'));

        if(
            Signature::check($request->all()) &&
            $request->order_status === 'approved' &&
            $request->currency === 'USD' &&
            $request->response_status === 'success'
        ) {
            $user_id = json_decode($request->merchant_data)[0]->value;
//            $amount = $request->amount / 100;
            $amount = $request->amount;

            $user = User::findOrFail($user_id);

            $user->deposit($amount);
        }

        return response('OK', 200);
    }
}
