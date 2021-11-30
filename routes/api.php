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
Route::put('reset-password', [AuthController::class, 'resetPassword']);

// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('movies/{groupId}/group', [MovieController::class, 'getGroupMovies']);
    Route::post('movies/user-info', [MovieController::class, 'getUserInfo']);
    Route::post('movies/{groupId}/add', [MovieController::class, 'store']);
    Route::put('movies/mark-as-seen', [MovieController::class, 'markMovieAsSeen']);
    Route::put('movies/rate', [MovieController::class, 'rateMovie']);
    Route::post('logout', [AuthController::class, 'logout']);
}); 