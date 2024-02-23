<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middlewareGroups' => ['jwt.verify']], function() {
    Route::get('users', UserController::class.'@index');
    Route::get('users/{id}', UserController::class.'@show');
    Route::post('users', UserController::class.'@store');
    Route::put('users/{id}', UserController::class.'@update');
    Route::delete('users/{id}', UserController::class.'@destroy');
});



Route::post('auth/signup', AuthController::class.'@signup');
Route::post('auth/login', AuthController::class.'@authenticate');
