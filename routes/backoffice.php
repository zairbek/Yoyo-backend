<?php

use App\Containers\Authentication\UI\API\Backoffice\Controllers\RefreshController;
use App\Containers\Authentication\UI\API\Backoffice\Controllers\SignInController;
use App\Containers\Authentication\UI\API\Backoffice\Controllers\SignOutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes For Admin Panel
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['as' => 'backoffice.'], static function () {
    Route::group(['prefix' => 'auth'], static function () {
        Route::group(['middleware' => ['client.credentials']], static function () {
            Route::post('sign-in', [SignInController::class, 'signIn'])->name('signIn');
            Route::post('refresh-token', [RefreshController::class, 'refreshToken'])->name('refreshToken');
        });
        Route::get('sign-out', [SignOutController::class, 'signOut'])->middleware('auth:api')->name('signOut');
    });
});
