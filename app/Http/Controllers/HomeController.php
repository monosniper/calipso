<?php

namespace App\Http\Controllers;


use App\Mail\FeedbackAnswer;
use App\Models\Category;
use App\Models\Feedback;
use App\Models\Lot;
use App\Models\Order;
use App\Models\User;
use App\Notifications\MessageReceived;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Tags\Tag;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::forShop()->get();
        $tags = Tag::getWithType(Lot::class);
        $premium_lots = Lot::premium()->active()->latest()->limit(3)->get();
        $top_freelancers = User::freelancers()->orderBy('rating', 'desc')->limit(4)->get();

        return view('home', [
            'premium_lots' => $premium_lots,
            'categories' => $categories,
            'tags' => $tags,
            'top_freelancers' => $top_freelancers,
        ]);
    }

    public function about()
    {
        $user = User::where('email', 'vanteatea8@gmail.com')->first();
        $user->notify(new MessageReceived($message));
        return view('about');
    }

    public function pricing()
    {
        return view('pricing');
    }

    public function projects()
    {
        return view('projects');
    }

    public function locale($locale)
    {
        return back()->cookie('locale', $locale);
    }

    public function reviews()
    {
        return view('reviews');
    }
}
