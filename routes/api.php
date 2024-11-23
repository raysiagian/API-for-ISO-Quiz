<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AdminController;

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

// User

// Login dan Register User
Route::post('register', [UserController::class,'register']);
Route::post('login', [UserController::class,'login']);

// Check ketersediaan email
Route::post('checkEmail', [UserController::class, 'checkEmailAvailability']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Admin

// Login dan Register Admin
Route::post('registerAdmin', [AdminController::class,'registerAdmin']);
Route::post('loginAdmin', [AdminController::class,'loginAdmin']);

// Check ketersediaan email
Route::post('checkEmailAdmin', [AdminController::class, 'checkEmailAvailabilityAdmin']);

Route::middleware('auth:sanctum')->get('/admin', function (Request $request) {
    return $request->admin();
});
