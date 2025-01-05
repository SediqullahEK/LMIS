<?php

use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomAuthenticatedSessionController;
use App\Http\Controllers\Auth\CustomRegisteredUserController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;


Route::get('/', function () {
    return view('pages.auth.login');
});

Route::post('/login', [CustomAuthenticatedSessionController::class, 'store'])->name('login');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/changePassword', [AppController::class, 'changePassword'])->middleware('auth')->name('first_login_change_password');

Route::group(['middleware' => ['auth', 'redirect.unauthenticated', 'checkFirstLogin']], function () {

    Route::get('/logout', function () {
        abort(404);
    });
    Route::get('/user/profile', [AppController::class, 'userProfile'])->name('user_profile');
    Route::get('/register', [CustomRegisteredUserController::class, 'create'])->name('register');
    Route::get('/user/management', [AppController::class, 'userManagement'])->middleware('permission:مدیریت کاربران')->name('user_management');

    Route::get('/activity-logs', [AppController::class, 'activityLog'])->name('activity_logs');
    Route::get('/applicants/individuals', [AppController::class, 'individuals'])->name('individuals');
    Route::get('/applicants/companies', [AppController::class, 'companies'])->name('companies');

    Route::get('/dashboard', [AppController::class, 'dashboard'])->name('dashboard');
});
