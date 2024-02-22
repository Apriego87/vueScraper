<?php

use App\Http\Controllers\ScraperController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('newspapers', ScraperController::class);
// Route::put('/update', [ScraperController::class, 'update']);
Route::post('/readByName', [ScraperController::class, 'readByName']);

/* Route::get('/index', [ScraperController::class, 'readAll']);

Route::post('/add', [ScraperController::class, 'add']);
Route::delete('/delete', [ScraperController::class, 'destroy']); */
