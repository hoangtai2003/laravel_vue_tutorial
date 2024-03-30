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
    Route::get('list/users', [UserController::class, 'index']);
    Route::post('create/users', [UserController::class, 'create']);
    Route::put('update/users/{user}', [UserController::class, 'update']);
    Route::delete('destroy/users/{user}', [UserController::class, 'destroy']);
});
Route::get('{view}', ApplicationController::class)->where('view', '(.*)');
