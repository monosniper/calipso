<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\FreelanceController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\LotController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// ================ MAIN PAGES ==================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/projects', [HomeController::class, 'projects'])->name('projects');
Route::get('/reviews', [HomeController::class, 'reviews'])->name('reviews');
// ================ MAIN PAGES ==================


// ================ FREELANCE ==================
Route::name('freelance.')->prefix('freelance')->group(function () {
    Route::get('/', [FreelanceController::class, 'index'])->name('index');
    Route::get('/freelancer/{user}', [FreelanceController::class, 'freelancer'])->name('freelancer');
    Route::get('/employer/{user}', [FreelanceController::class, 'employer'])->name('employer');

    Route::get('/safe/{order}', [FreelanceController::class, 'safe'])->name('safe');

    Route::name('freelancer.')->prefix('freelancer/{user}')->group(function () {
        Route::get('/lots', [FreelanceController::class, 'freelancerLots'])->name('lots');
        Route::get('/orders', [FreelanceController::class, 'freelancerOrders'])->name('orders');
        Route::get('/reviews', [FreelanceController::class, 'freelancerReviews'])->name('reviews');
    });

    Route::name('employer.')->prefix('employer/{user}')->group(function () {
        Route::get('/lots', [FreelanceController::class, 'employerLots'])->name('lots');
        Route::get('/orders', [FreelanceController::class, 'employerOrders'])->name('orders');
    });

    Route::name('orders.')->prefix('orders/{order}')->middleware('auth')->group(function () {
        Route::get('/choose_offer/{offer}', [OrderController::class, 'chooseOffer'])->name('choose_offer');
        Route::get('/agree', [OrderController::class, 'agree'])->name('agree');
        Route::get('/reserve', [OrderController::class, 'reserve'])->name('reserve');
        Route::get('/close', [OrderController::class, 'closeOrder'])->name('close');
    });
});
// ================ FREELANCE ==================


// ================ FORMS ==================
Route::name('forms.')->prefix('forms')->group(function () {
    Route::post('/safe', [FormsController::class, 'safe'])->middleware('auth')->name('safe');
    Route::post('/safe_result', [FormsController::class, 'safeResult'])->middleware('auth')->name('safe_result');
    Route::post('/resume', [FormsController::class, 'resume'])->middleware('auth')->name('resume');
    Route::post('/profile', [FormsController::class, 'profile'])->middleware('auth')->name('profile');
    Route::post('/avatar', [FormsController::class, 'avatar'])->middleware('auth')->name('avatar');
    Route::post('/feedback', [FormsController::class, 'feedback'])->name('feedback');
    Route::any('/filepond_avatar', [FormsController::class, 'filepond_avatar'])->middleware('auth')->name('filepond_avatar');
    Route::post('/report', [FormsController::class, 'report'])->middleware('auth')->name('report');
});
// ================ FORMS ==================


// ================ DASHBOARD ==================
Route::name('dashboard.')
    ->prefix('dashboard')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('cabinet');
        Route::get('/edit', [DashboardController::class, 'editProfile'])->name('edit-profile');
        Route::post('/edit', [DashboardController::class, 'editProfileProcess']);
        Route::get('/change-password', [DashboardController::class, 'changePassword'])->name('change-password');
        Route::post('/change-password', [DashboardController::class, 'changePasswordProcess']);
        Route::get('/archive', [DashboardController::class, 'archive'])->name('archive');
        Route::get('/basket', [DashboardController::class, 'basket'])->name('basket');
        Route::get('/wishlist', [DashboardController::class, 'wishlist'])->name('wishlist');
        Route::get('/lots', [DashboardController::class, 'lots'])->name('lots');
        Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
        Route::get('/work', [DashboardController::class, 'work'])->name('work');
        Route::get('/pay-history', [DashboardController::class, 'payHistory'])->name('pay-history');
        Route::get('/withdraw', [DashboardController::class, 'withdraw'])->name('withdraw');
        Route::get('/be-freelancer', [DashboardController::class, 'freelancer'])->name('freelancer');
});
// ================ DASHBOARD ==================


// ================ HELP ==================
Route::name('help.')
    ->prefix('help')
    ->group(function () {
        Route::get('/', [HelpController::class, 'index'])->name('index');
        Route::get('/company', [HelpController::class, 'company'])->name('company');
        Route::get('/freelance', [HelpController::class, 'freelance'])->name('freelance');
        Route::get('/shop', [HelpController::class, 'shop'])->name('shop');
        Route::get('/policy', [HelpController::class, 'policy'])->name('policy');
        Route::get('/conditions', [HelpController::class, 'conditions'])->name('conditions');
        Route::get('/sitemap', [HelpController::class, 'sitemap'])->name('sitemap');
    });
// ================ HELP ==================


// ================ CHAT ==================
Route::name('chat.')
    ->prefix('chat')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('{conversation}', [ChatController::class, 'conversation'])->name('conversation');

        Route::name('add.')
            ->prefix('add')
            ->group(function() {
                Route::get('conversation/{companion_id}', [ChatController::class, 'addConversation'])->name('conversation');
                Route::post('message', [ChatController::class, 'addMessage'])->name('message');
            });
    });
// ================ CHAT ==================


// ================ RESOURCES ==================
Route::resource('lots', LotController::class);
Route::resource('categories', CategoryController::class)->except(['index', 'create', 'edit']);
Route::resource('reviews', ReviewController::class)->only(['store', 'update', 'destroy']);
Route::resource('orders', OrderController::class);
Route::resource('offers', OfferController::class)->middleware('auth');
Route::resource('portfolios', PortfolioController::class)->middleware('auth');

Route::get('lots/{lot}/statistics', [LotController::class, 'statistics'])->name('lots.statistics');
Route::get('lots/{lot}/get', [LotController::class, 'getArchive'])->middleware('auth')->name('lots.get');
// ================ RESOURCES ==================

Route::name('cart.')
    ->prefix('cart')
    ->middleware('auth')
    ->group(function () {
        Route::get('/pay', [CartController::class, 'pay'])->name('pay');
    });

// ================ OTHER ==================
require __DIR__ . '/auth.php';
Route::get('/locale/{locale}', [HomeController::class, 'locale'])->name('locale');
// ================ OTHER ==================
