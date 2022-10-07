<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;

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


/**
 * The Version 1 route declarations
 */
Route::group(['middleware' => ['set-header'], 'prefix' => 'v1'], function () {

    /**
     * Onboarding section
     */
    Route::group(['prefix' => 'onboard'], function (){
        Route::get('welcome', [OnboardingController::class, 'welcome']);
        Route::post('login', [OnboardingController::class, 'login']);
        Route::post('signup', [OnboardingController::class, 'signup']);
        Route::get('verify', [OnboardingController::class, 'verifyUser']);
    });
    /**
     * Authenticated section
     */
    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['prefix' => 'users'], function () {
            Route::get('detail', [UserController::class, 'userDetails']);
            Route::get('logout', [UserController::class, 'logout']);
        });

        Route::group(['prefix' => 'posts'], function () {
            Route::post('create', [PostController::class, 'newPost']);
            Route::post('/{post_id}/comment', [PostController::class, 'createComment']);
            Route::get('/{post_id}/get', [PostController::class, 'getPostById']);
            Route::delete('/{post_id}/delete', [PostController::class, 'deletePostById']);
        });

    });

});
