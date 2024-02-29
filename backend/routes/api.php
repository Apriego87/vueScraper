<?php

use App\Http\Controllers\ScraperController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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


Route::group([
    'middleware' => ['auth:api']
], function () {
    Route::resource('newspapers', ScraperController::class);

    Route::get('getNames', [ScraperController::class, 'getNames']);
    Route::post('userNewspapers', [ScraperController::class, 'userNewspapers']);
    Route::post('readByName', [ScraperController::class, 'readByName']);
    Route::post('getNpData', [ScraperController::class, 'getNpData']);
});

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::group([
    'middleware' => ['auth:api']
], function () {
    Route::get('profile', [UserController::class, 'profile']);
    Route::get('logout', [UserController::class, 'logout']);
});



/* Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
}); */

/* Route::get('/index', [ScraperController::class, 'readAll']);
// Route::put('/update', [ScraperController::class, 'update']);

Route::post('/add', [ScraperController::class, 'add']);
Route::delete('/delete', [ScraperController::class, 'destroy']); */
