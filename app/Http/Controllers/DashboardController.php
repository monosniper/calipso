<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Category;
use App\Models\Lot;
use App\Models\Order;
use App\Models\Portfolio;
//use App\Models\Transaction;
use App\Models\User;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Models\Wallet;
use EloquentBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Cart;

class DashboardController extends Controller
{

    use AuthUserWithCountsController;

    public function index(Request $request)
    {
        $user = $this->getAuthUserWithCounts();
        $portfolios = EloquentBuilder::to(Portfolio::where('user_id', $user->id)->latest(), $request->only(['tag']))->get();

        return view('dashboard.index', [
            'user' => $user,
            'portfolios' => $portfolios,

        ]);
    }

    public function editProfile() {
        $user = $this->getAuthUserWithCounts();
        $categories = Category::forFreelance()->get();

        return view('dashboard.edit', [
            'user' => $user,
            'categories' => $categories,
        ]);
    }

    public function editProfileProcess(UpdateUserRequest $request) {
        $user = User::findOrFail($request->user_id);
        $user->update($request->validated());

        if($request->filled('categories')) {
            $user->categories()->detach();
            $user->categories()->attach($request->categories);
        }

        return back()->with('success', __('messages.user.updated'));
    }

    public function changePassword(Request $req)
    {
        $user = $this->getAuthUserWithCounts();

        return view('dashboard.changePassword', [
            'user' => $user,
        ]);
    }

    public function changePasswordProcess(ChangePasswordRequest $request) {
        $user = auth()->user();

        if(Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return back()->with('success',__('messages.user.password_updated'));
        }

        return back()->with('error', 'Старый пароль неверный.');
    }

    public function archive(Request $req)
    {
        $user = $this->getAuthUserWithCounts(false)->with(['purchasedLots' => function($query) {
            $query->latest();
        }])->findOrFail(auth()->id());

        return view('dashboard.archive', [
            'user' => $user,
            'lot_purchases' => $user->purchasedLots->paginate(9),
        ]);
    }

    public function basket(Request $req)
    {
        $user = $this->getAuthUserWithCounts();
        return view('dashboard.basket', [
            'user' => $user,
            'total_sum' => Cart::instance('basket')->subtotal(),
        ]);
    }

    public function wishlist(Request $req)
    {
        $user = $this->getAuthUserWithCounts();
        return view('dashboard.wishlist', [
            'user' => $user,
        ]);
    }

    public function lots(Request $req)
    {
        $user = $this->getAuthUserWithCounts(false)->with(['lots' => function($query) {
            $query->withoutGlobalScopes()->withAvg('ratings', 'rating')->with(['category' => function($query) {
                $query->with(['lots' => function($query) {
                    $query->without('media');
                }]);
            }])->latest();
        }])->findOrFail(auth()->id());

        return view('dashboard.lots', [
            'user' => $user,
            'lots' => $user->lots->paginate(9),
        ]);
    }

    public function orders(Request $req)
    {
        $user = $this->getAuthUserWithCounts(false)->with(['orders' => function($query) {
            $query->withoutGlobalScopes()->with('tags')->withCount('offers')->latest();
        }])->findOrFail(auth()->id());

        return view('dashboard.orders', [
            'user' => $user,
            'orders' => $user->orders->paginate(9),
        ]);
    }

    public function payHistory(Request $req)
    {
        $user = $this->getAuthUserWithCounts();

        $transactions = Transaction::where([
            ['payable_type', User::class],
            ['payable_id', $user->id],
        ]);

        if($req->has('sort')) {
            $transactions->orderBy($req->sort, $req->direction ?? 'asc');
        }

        $transactions = $transactions->latest()->paginate(12);

        return view('dashboard.payHistory', [
            'user' => $user,
            'transactions' => $transactions,
        ]);
    }

    public function withdraw(Request $req)
    {
        $user = $this->getAuthUserWithCounts();
        return view('dashboard.withdraw', [
            'user' => $user,
        ]);
    }

    public function freelancer(Request $req)
    {
        $user = $this->getAuthUserWithCounts();
        return view('dashboard.freelancer', [
            'user' => $user,
        ]);
    }

    public function work(Request $req)
    {
        $user = $this->getAuthUserWithCounts();

        $orders = Order::where(function ($query) use($user) {
            $query
                ->where('user_id', $user->id)
                ->orWhere('freelancer_id', $user->id);
        })->inWork()->latest()->paginate(12);

        return view('dashboard.work', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }
}
