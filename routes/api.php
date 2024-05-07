<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
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
    Route::get('{appointment}/edit', [AppointmentController::class, 'edit']);
    Route::put('{appointment}/update', [AppointmentController::class, 'update']);
    Route::delete('{appointment}/delete', [AppointmentController::class, 'delete']);
});
Route::prefix('clients')->group(function (){
    Route::get('list', [ClientController::class, 'list']);
});
Route::prefix('dashboard')->group(function (){
    Route::get('appointments', [DashboardController::class, 'appointments']);
    Route::get('users', [DashboardController::class, 'users']);
});
Route::prefix('settings')->group(function (){
    Route::get('list', [SettingController::class, 'list']);
    Route::put('update', [SettingController::class, 'update']);
});
Route::prefix('profile')->group(function (){
    Route::get('users', [ProfileController::class, 'user']);
    Route::put('update', [ProfileController::class, 'update']);
    Route::post('upload-profile-image', [ProfileController::class, 'uploadImage']);
    Route::post('change-user-password', [ProfileController::class, 'changePassword']);
});
Route::prefix('auth')->group(function (){
    Route::post('/login', [AuthController::class, 'login'])->name('handleLogin');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/change-pass', [AuthController::class, 'changePassWord']);
});
