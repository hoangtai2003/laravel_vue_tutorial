<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api')->group(function () {
    Route::get('users/list', [UserController::class, 'index']);
    Route::post('users/create', [UserController::class, 'create']);
    Route::put('users/update/{user}', [UserController::class, 'update']);
    Route::delete('users/destroy/{user}', [UserController::class, 'destroy']);
    Route::patch('users/change-role/{user}', [UserController::class, 'changeRole']);
});
Route::get('{view}', ApplicationController::class)->where('view', '(.*)');
