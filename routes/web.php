<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserPrivilegeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [AuthController::class, 'showFormLogin'])->name('login');
Route::get('login', [AuthController::class, 'showFormLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth'], function () {
    // auth
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    // User Privilege
    Route::get('user/privilege/trash', [UserPrivilegeController::class, 'trash']);
    Route::get('user/privilege/restore/{id?}', [UserPrivilegeController::class, 'restore']);
    Route::get('user/privilege/delete/{id?}', [UserPrivilegeController::class, 'delete']);

    // User
    Route::get('user/trash', [UserController::class, 'trash']);
    Route::get('user/restore/{id?}', [UserController::class, 'restore']);
    Route::get('user/delete/{id?}', [UserController::class, 'delete']);

    // resources route
    Route::resources([
        'user/privilege' => UserPrivilegeController::class,
        'user' => UserController::class
    ]);
});
