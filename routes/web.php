<?php

use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
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
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
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
// Trang yêu cầu xác minh
Route::get('/email/verify', function () {
    return view('auth.verify-email'); // Tạo view này để hướng dẫn người dùng kiểm tra mail
})->middleware('auth')->name('verification.notice');

// Xác minh email khi user nhấn link
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // Đánh dấu email_verified_at
    return redirect(\route('dashboard')); // hoặc route khác
})->middleware(['auth', 'signed'])->name('verification.verify');

// Gửi lại email xác minh
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Đã gửi, vui lòng kiểm tra email!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.forgot');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

Route::post('/login', [AuthController::class, 'login'])->name('login.request');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:6,1')->name('register.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.forgot.request');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset.request');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'verified', 'auth.user'])->prefix('admin')->group(function () {
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
        ->only(['index', 'show', 'update', 'store', 'edit', 'create'])
        ->names('admin.services');

    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');

    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.readAll');

    Route::resource('/profile', ProfileController::class)->names('admin.profile')->only(['index', 'update']);
    Route::resource('/change-password', ChangePasswordController::class)->names('admin.change-password')->only(['index', 'update']);

    Route::middleware('auth.role:' . User::ROLE_CSKH)->group(function () {
        Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class)->only(['destroy'])->names('admin.services');
        Route::resource('products', ProductController::class)->names('admin.products');
        Route::get('products/{id}/history', [ProductController::class, 'history'])->name('admin.products.history');
        Route::resource('repairman', RepairmanController::class)->names('admin.repairman');
        Route::resource('customers', CustomerController::class)->names('admin.customers');

        Route::middleware('auth.role:' . User::ROLE_CSKH)->group(function () {
            Route::resource('users', UserController::class)->names('admin.users');
        });
    });

    /*Route::view('/pages/slick', 'pages.slick');
    Route::view('/pages/datatables', 'pages.datatables');
    Route::view('/pages/blank', 'pages.blank');*/
});

Route::group([], function () {
    Route::get('/', [TrackEmailController::class, 'index']);
    Route::post('/', [TrackEmailController::class, 'trackEmail'])->middleware('throttle:6,1')->name('track-email');

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


