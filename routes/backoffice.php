<?php

use App\Containers\Authentication\UI\API\Backoffice\Controllers\SignInController;
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

Route::group(['as' => 'backoffice.', 'middleware' => ['client.credentials']], static function () {
    Route::group(['prefix' => 'auth'], static function () {
        Route::post('sign-in', [SignInController::class, 'signIn'])->name('signIn');
    });
});
