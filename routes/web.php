<?php

use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RepairmanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\TrackEmailController;
use App\Http\Controllers\Web\ServiceController;
use App\Models\Customer;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Example Routes

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.forgot');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

Route::post('/login', [AuthController::class, 'login'])->name('login.request');
Route::post('/register', [AuthController::class, 'register'])->name('register.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.forgot.request');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset.request');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::match(['get', 'post'], '/', function () {
        return redirect(\route('dashboard'));
    });

    Route::match(['get', 'post'], '/dashboard', function () {
        $cskh = User::where('role', User::ROLE_CSKH)->count();
        $repairman = User::where('role', User::ROLE_REPAIRMAN)->count();
        $customer = Customer::count();
        $service = Service::count();
        return view('dashboard', compact('cskh', 'repairman', 'customer', 'service'));
    })->name('dashboard');

    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class)
        ->only(['index', 'show', 'update', 'edit', 'create'])
        ->names('admin.services');

    Route::middleware('auth.isAdmin')->group(function () {
        // Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class)->except(['destroy'])->names('admin.services');
        Route::resource('products', ProductController::class)->names('admin.products');
        Route::get('products/{id}/history', [ProductController::class, 'history'])->name('admin.products.history');
        Route::resource('repairman', RepairmanController::class)->names('admin.repairman');
        Route::resource('users', UserController::class)->names('admin.users');
        Route::resource('customers', CustomerController::class)->names('admin.customers');
        Route::resource('/change-password', ChangePasswordController::class)->names('admin.change-password')->only(['index', 'update']);
    });

    /*Route::view('/pages/slick', 'pages.slick');
    Route::view('/pages/datatables', 'pages.datatables');
    Route::view('/pages/blank', 'pages.blank');*/
});

Route::group([], function () {
    Route::get('/', [TrackEmailController::class, 'index']);
    Route::post('/', [TrackEmailController::class, 'trackEmail'])->name('track-email');

    Route::middleware('auth.customer')->prefix('track/{email}')->group(function () {
        Route::prefix('/orders')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('orders.index');
            Route::get('/{id}/history', [OrderController::class, 'history'])->name('orders.history');
        });

        Route::prefix('/services')->group(function () {
            Route::get('/', [ServiceController::class, 'index'])->name('services.index');
            Route::get('/{orderId}/create', [ServiceController::class, 'request'])->name('services.request');
            Route::post('/{orderId}/create', [ServiceController::class, 'create'])->name('services.create');
            Route::get('/{id}', [ServiceController::class, 'detail'])->name('services.detail');
            Route::post('/{id}/review', [ServiceController::class, 'review'])->name('services.review');
        });
    });
});


