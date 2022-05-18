<?php

use App\Containers\Account\UI\API\Public\Controllers\AccountController;
use App\Containers\Authentication\UI\API\Public\Controllers\RefreshController;
use App\Containers\Authentication\UI\API\Public\Controllers\SignInController;
use App\Containers\Authentication\UI\API\Public\Controllers\SignOutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1', 'as' => 'public.'], static function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], static function () {
        Route::group(['middleware' => ['client.credentials']], static function () {
            Route::post('send', [SignInController::class, 'send'])->name('send');
            Route::post('sign-in', [SignInController::class, 'signIn'])->name('signIn');
            Route::post('refresh-token', [RefreshController::class, 'refreshToken'])->name('refreshToken');
        });
        Route::get('sign-out', [SignOutController::class, 'signOut'])->middleware('auth:api')->name('signOut');
    });

    Route::group(['middleware' => ['auth:api', 'account.status']], static function () {
        Route::group(['prefix' => 'account'], static function () {
            Route::get('/', [AccountController::class, 'get'])->name('account');
        });
    });

    Route::get('test', static function () {

    });
});
