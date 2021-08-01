<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('movies/group', [MovieController::class, 'getGroupMovies']);
    Route::post('user-info', [ProductController::class, 'getUserInfo']);
    Route::post('movies/add', [ProductController::class, 'store']);
    Route::put('movies/mark-as-seen', [ProductController::class, 'markMoviesSeen']);
    Route::post('logout', [AuthController::class, 'logout']);
}); 