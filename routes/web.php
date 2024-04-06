<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClientController;
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
    Route::prefix('users')->group(function (){
        Route::get('list', [UserController::class, 'index']);
        Route::post('create', [UserController::class, 'create']);
        Route::put('update/{user}', [UserController::class, 'update']);
        Route::delete('destroy/{user}', [UserController::class, 'destroy']);
        Route::patch('change-role/{user}', [UserController::class, 'changeRole']);
        Route::get('search', [UserController::class, 'search']);
        Route::delete('', [UserController::class, 'bulkDelete']);
    });

    Route::prefix('appointments')->group(function (){
       Route::get('list', [AppointmentController::class, 'list']);
       Route::get('appointment-status', [AppointmentController::class, 'getStatusWithCount']);
       Route::post('create', [AppointmentController::class, 'create']);
    });
    Route::prefix('clients')->group(function (){
        Route::get('list', [ClientController::class, 'list']);

    });
});
Route::get('{view}', ApplicationController::class)->where('view', '(.*)');
