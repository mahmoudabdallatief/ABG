<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/profile', [AuthorController::class, 'profile'])->name('profile');
Route::get('/profile_user/{id}', [AuthorController::class, 'profile_user'])->name('profile_user');
Route::post('/edit_picture', [AuthorController::class, 'edit_picture'])->name('edit_picture');
Route::post('/sendRequest', [AuthorController::class, 'sendRequest'])->name('sendRequest');
Route::post('/acceptRequest', [AuthorController::class, 'acceptRequest'])->name('acceptRequest');
Route::post('/rejectRequest', [AuthorController::class, 'rejectRequest'])->name('rejectRequest');
Route::post('/searchFriends', [AuthorController::class, 'searchFriends'])->name('searchFriends');
Route::post('/addpost', [HomeController::class, 'addpost'])->name('addpost');
Route::post('/editpost', [HomeController::class, 'editpost'])->name('editpost');
Route::put('/updatepost/{id}', [HomeController::class, 'updatepost'])->name('updatepost');
Route::delete('/deletepost/{id}', [HomeController::class, 'deletepost'])->name('deletepost');
Route::get('/singlepost/{id}', [HomeController::class, 'singlepost'])->name('singlepost');

Route::post('/addlike', [HomeController::class, 'addlike'])->name('addlike');
Route::post('/addcomment', [HomeController::class, 'addcomment'])->name('addcomment');
Route::post('/editcomment', [HomeController::class, 'editcomment'])->name('editcomment');
Route::put('/updatecomment/{id}', [HomeController::class, 'updatecomment'])->name('updatecomment');
Route::delete('/deletecomment/{id}', [HomeController::class, 'deletecomment'])->name('deletecomment');
