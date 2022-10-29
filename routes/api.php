<?php

use App\Http\Controllers\Api\FeedBackApiController;
use App\Http\Controllers\Api\OfferApiController;
use App\Http\Controllers\Api\QuestionApiController;
use App\Http\Controllers\Api\TagApiController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PayController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\LotApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\OrderApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/sanctum/token', [AuthenticatedSessionController::class, 'getApiToken']);
Route::get('/me', [AuthenticatedSessionController::class, 'getMe'])->middleware('auth:sanctum');
Route::post('/pay-callback', [PayController::class, 'payCallback']);

Route::group(['as' => 'api.'], function() {
    Route::middleware('auth:sanctum')->group(function () {

        Route::controller(CategoryApiController::class)->group(function() {
            Route::get('/categories/{for}', 'index');
            Route::post('/categories/{for}', 'store');
            Route::get('/categories/{for}/{category}', 'show');
            Route::put('/categories/{for}/{category}', 'update');
            Route::delete('/categories/{for}/{category}', 'destroy');
        });

        Route::apiResources([
            'lots' => LotApiController::class,
            'orders' => OrderApiController::class,
            'reviews' => OrderApiController::class,
            'users' => UserApiController::class,
            'tags' => TagApiController::class,
            'offers' => OfferApiController::class,
            'feedBacks' => FeedBackApiController::class,
            'questions' => QuestionApiController::class,
        ]);

    });
});
