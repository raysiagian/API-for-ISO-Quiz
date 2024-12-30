<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\QuizCategoryController;
use App\Http\Controllers\Api\QuizSubCategoryController;
use App\Http\Controllers\Api\QuizMaterialController;
use App\Http\Controllers\Api\QuizQuestionController;
use App\Http\Controllers\Api\ScoreQuizController;

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
Route::post('login', [UserController::class, 'login'])->name('login');;

Route::post('checkEmail', [UserController::class, 'checkEmailAvailability']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [UserController::class, 'user']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/logout', [UserController::class, 'logout']);
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


// Category
Route::post('addCategory',[QuizCategoryController::class, "store"]);
Route::get('getCategory', [QuizCategoryController::class, 'index']);
Route::post('editCategory/{id}', [QuizCategoryController::class, 'update']);
Route::delete('deleteCategory/{id}', [QuizCategoryController::class, 'destroy']);
Route::get('category/{id}', [QuizCategoryController::class, 'show']);


// subcateory
Route::post('addSubCategory',[QuizSubCategoryController::class, "store"]);
Route::get('getSubCategory', [QuizSubCategoryController::class, 'index']);
Route::get('getSubCategoryByIdCategory', [QuizSubCategoryController::class, 'subcategorybyIdCategory']);
Route::post('editSubCategory/{id}', [QuizSubCategoryController::class, 'update']);
Route::delete('deleteSubCategory/{id}', [QuizSubCategoryController::class, 'destroy']);
Route::get('subcategory/{id}'  , [QuizSubCategoryController::class, 'show']);


// quiz material
Route::post('addMaterial',[QuizMaterialController::class, "store"]);
Route::get('getMaterial', [QuizMaterialController::class, 'index']);
Route::get('getMaterialbyIdSubCategory', [QuizMaterialController::class, 'materialbyIdSubCategory']);
Route::post('editMaterial/{id}', [QuizMaterialController::class, 'update']);
Route::delete('deleteMaterial/{id}', [QuizMaterialController::class, 'destroy']);
Route::get('material/{id}'  , [QuizMaterialController::class, 'show']);



// 2 controller berikut belum ditest pada postman

// quiz question
Route::post('addQuestion',[QuizQuestionController::class, "store"]);
Route::get('getQuestion', [QuizQuestionController::class, 'index']);
Route::get('getQuestionbyIdSubCategory', [QuizQuestionController::class, 'questionbyIdSubCategory']);
Route::post('editQuestion/{id}', [QuizQuestionController::class, 'update']);
Route::delete('deleteQuestion/{id}', [QuizQuestionController::class, 'destroy']);
Route::get('question/{id}'  , [QuizQuestionController::class, 'show']);


// score
Route::get('/scorequiz', [ScoreQuizController::class, 'index']);
Route::get('/scorequiz/{id_user}/{id_quizsubCategory}', [ScoreQuizController::class, 'show']);
Route::post('/scorequiz', [ScoreQuizController::class, 'updateOrInsert']);
Route::delete('/scorequiz/{id_scoreQuiz}', [ScoreQuizController::class, 'delete']);
Route::get('/scorequiz/history/{id_user}', [ScoreQuizController::class, 'showHistory']);
Route::get('/scorequiz/total/{id_user}', [ScoreQuizController::class, 'totalScore']);
Route::get('/scorequiz/leaderboard', [ScoreQuizController::class, 'leaderboard']);
Route::get('/user/leaderboard/{userId}', [ScoreQuizController::class, 'userLeaderboard']);