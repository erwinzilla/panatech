<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserPrivilegeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchServiceController;
use App\Http\Controllers\BranchCoordinatorController;
use App\Http\Controllers\BranchController;
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
Route::get('theme/{mode}', [HomeController::class, 'theme']);
Route::get('theme/{mode}/icon', [HomeController::class, 'themeIcon']);

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
    Route::get('user/choose', [UserController::class, 'chooseUser']);

    // Branch Coordinator
    Route::get('branch/service/trash', [BranchServiceController::class, 'trash']);
    Route::get('branch/service/restore/{id?}', [BranchServiceController::class, 'restore']);
    Route::get('branch/service/delete/{id?}', [BranchServiceController::class, 'delete']);

    // Branch Coordinator
    Route::get('branch/coordinator/trash', [BranchCoordinatorController::class, 'trash']);
    Route::get('branch/coordinator/restore/{id?}', [BranchCoordinatorController::class, 'restore']);
    Route::get('branch/coordinator/delete/{id?}', [BranchCoordinatorController::class, 'delete']);

    // Branch
    Route::get('branch/trash', [BranchController::class, 'trash']);
    Route::get('branch/restore/{id?}', [BranchController::class, 'restore']);
    Route::get('branch/delete/{id?}', [BranchController::class, 'delete']);
    Route::get('branch/choose', [BranchController::class, 'choose']);


    // resources route
    Route::resources([
        'user/privilege'     => UserPrivilegeController::class,
        'user'               => UserController::class,
        'branch/service'     => BranchServiceController::class,
        'branch/coordinator' => BranchCoordinatorController::class,
        'branch'             => BranchController::class
    ]);
});