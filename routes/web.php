<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;

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
    return view('welcome');
});
Route::get('/register', [UserAuthController::class, 'register'])->name('register');
Route::post('/register-post', [UserAuthController::class, 'registerPost'])->name('registration.submit');
Route::get('/users-list', [UserAuthController::class, 'usersList'])->name('users.list');
Route::get('/users-edit/{id}', [UserAuthController::class, 'userEdit'])->name('users.edit');
Route::get('/users-delete/{id}', [UserAuthController::class, 'userDestroy'])->name('users.destroy');
Route::post('/update-user', [UserAuthController::class, 'updateUser'])->name('users.update');
Route::post('/get-user-detail', [UserAuthController::class, 'grtUserDetail'])->name('user.details');






