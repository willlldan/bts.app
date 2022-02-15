<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShoppingController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Sign Up Route
Route::post('/users/signup', [AuthController::class, 'signup']);

// Sign in route
Route::post('/users/signin', [AuthController::class, 'signin']);

// Check alreadi Sign in

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('/shopping', ShoppingController::class)->except(['create', 'edit']);


    // API route for logout user
    // Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
});
