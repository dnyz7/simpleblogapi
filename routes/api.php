<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\User\RoleController;

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

Route::post('login', [AuthController::class, 'signin']);
Route::post('register', [AuthController::class, 'signup']);


Route::middleware('auth:sanctum','json.response')->group( function () {
    Route::group(['middleware' => ['json.response','role:admin']], function () {
        Route::resource('users', RegisterController::class);
        Route::post('users/create', [RegisterController::class, 'create']);
        Route::post('users/edit/{id}', [RegisterController::class,'update']); // update a user
        Route::post('users/delete/{id}', [RegisterController::class,'delete']); // delete a user
        Route::get('users/{id}', [RegisterController::class, 'show']); // get a post

        Route::resource('roles', RoleController::class);
        Route::get('roles/{id}', [RoleController::class, 'show']); // get a post
        Route::post('roles/create', [RoleController::class, 'create']);
        Route::post('roles/edit/{id}', [RoleController::class,'update']); // update a user
        Route::post('roles/delete/{id}', [RoleController::class,'delete']); // delete a user
        
    });
    
    Route::post('logout', [AuthController::class, 'logout']);
    
    Route::resource('articles', ArticleController::class);
    Route::get('articles/{id}', [ArticleController::class,'show']);
    Route::post('articles/store', [ArticleController::class,'store']); // post an article
    Route::post('articles/edit/{id}', [ArticleController::class,'update']); // update an article
    Route::post('articles/delete/{id}', [ArticleController::class,'delete']); // update an article
    
});
