<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;

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


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'auth.json'])->group(function () {
    Route::get('/home', [HomeController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/profile_user/{id}', [AuthController::class, 'profile_user']);
    Route::post('/edit_picture', [AuthController::class, 'edit_picture']);
    Route::post('/sendRequest', [AuthController::class, 'sendRequest']);
    Route::post('/acceptRequest', [AuthController::class, 'acceptRequest']);
    Route::post('/rejectRequest', [AuthController::class, 'rejectRequest']);
    Route::post('/searchFriends', [AuthController::class, 'searchFriends']);
    Route::post('/addpost', [HomeController::class, 'addpost']);
    Route::post('/editpost', [HomeController::class, 'editpost']);
    Route::put('/updatepost/{id}', [HomeController::class, 'updatepost']);
    Route::delete('/deletepost/{id}', [HomeController::class, 'deletepost']);
    Route::get('/singlepost/{id}', [HomeController::class, 'singlepost']);
    Route::post('/addlike', [HomeController::class, 'addlike']);
    Route::post('/addcomment', [HomeController::class, 'addcomment']);
    Route::post('/editcomment', [HomeController::class, 'editcomment']);
    Route::put('/updatecomment/{id}', [HomeController::class, 'updatecomment']);
    Route::delete('/deletecomment/{id}', [HomeController::class, 'deletecomment']);
});



