<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserPrivilegeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchServiceController;
use App\Http\Controllers\BranchCoordinatorController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CustomerTypeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\WarrantyController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\JobTypeController;
use App\Http\Controllers\JobController;
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
    Route::get('user/profile/{id}', [UserController::class, 'profile']);

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

    // Customer
    Route::get('customer/type/trash', [CustomerTypeController::class, 'trash']);
    Route::get('customer/type/restore/{id?}', [CustomerTypeController::class, 'restore']);
    Route::get('customer/type/delete/{id?}', [CustomerTypeController::class, 'delete']);
    Route::get('customer/type/choose', [CustomerTypeController::class, 'choose']);
    Route::post('customer/type/validate', [CustomerTypeController::class, 'validateForm']);;


    // Customer
    Route::get('customer/trash', [CustomerController::class, 'trash']);
    Route::get('customer/restore/{id?}', [CustomerController::class, 'restore']);
    Route::get('customer/delete/{id?}', [CustomerController::class, 'delete']);;
    Route::post('customer/validate', [CustomerController::class, 'validateForm']);;

    // Warranty
    Route::get('warranty/create/{id?}', [WarrantyController::class, 'create']);
    Route::get('warranty/trash', [WarrantyController::class, 'trash']);
    Route::get('warranty/restore/{id?}', [WarrantyController::class, 'restore']);
    Route::get('warranty/delete/{id?}', [WarrantyController::class, 'delete']);

    // Ticket
    Route::get('ticket/create/{id?}', [TicketController::class, 'create']);
    Route::get('ticket/trash', [TicketController::class, 'trash']);
    Route::get('ticket/restore/{id?}', [TicketController::class, 'restore']);
    Route::get('ticket/delete/{id?}', [TicketController::class, 'delete']);

    // Ticket
    Route::get('status/trash', [StatusController::class, 'trash']);
    Route::get('status/restore/{id?}', [StatusController::class, 'restore']);
    Route::get('status/delete/{id?}', [StatusController::class, 'delete']);

    // Job Type
    Route::get('job/type/trash', [JobTypeController::class, 'trash']);
    Route::get('job/type/restore/{id?}', [JobTypeController::class, 'restore']);
    Route::get('job/type/delete/{id?}', [JobTypeController::class, 'delete']);

    // Job Type
    Route::get('job/create/{id?}', [JobController::class, 'create']);
    Route::get('job/trash', [JobController::class, 'trash']);
    Route::get('job/restore/{id?}', [JobController::class, 'restore']);
    Route::get('job/delete/{id?}', [JobController::class, 'delete']);

    // resources route
    Route::resources([
        'user/privilege'        => UserPrivilegeController::class,
        'user'                  => UserController::class,
        'branch/service'        => BranchServiceController::class,
        'branch/coordinator'    => BranchCoordinatorController::class,
        'branch'                => BranchController::class,
        'customer/type'         => CustomerTypeController::class,
        'customer'              => CustomerController::class,
        'warranty'              => WarrantyController::class,
        'ticket'                => TicketController::class,
        'status'                => StatusController::class,
        'job/type'              => JobTypeController::class,
        'job'                   => JobController::class,
    ]);
});