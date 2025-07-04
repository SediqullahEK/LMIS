<?php

use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomAuthenticatedSessionController;
use App\Http\Controllers\Auth\CustomRegisteredUserController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;


Route::get('/', function () {
    return view('pages.auth.login');
});
Route::get('/refresh-csrf', function () {
    return response()->json(['token' => csrf_token()]);
});
Route::post('/login', [CustomAuthenticatedSessionController::class, 'store'])->name('login');

Route::match(['get', 'post'], '/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/verification', [AppController::class, 'verification'])->name('verification');

Route::get('/changePassword', [AppController::class, 'changePassword'])->middleware('auth')->name('first_login_change_password');

Route::group(['middleware' => ['auth', 'checkFirstLogin']], function () {

    Route::get('/user/profile', [AppController::class, 'userProfile'])->name('user_profile');
    Route::get('/register', [CustomRegisteredUserController::class, 'create'])->name('register');
    Route::get('/user/management', [AppController::class, 'userManagement'])->middleware('permission:مدیریت کاربران')->name('user_management');

    Route::get('/dashboard', [AppController::class, 'dashboard'])->name('dashboard');
    Route::get('/applicants/individuals', [AppController::class, 'individuals'])->name('individuals');
    Route::get('/applicants/companies', [AppController::class, 'companies'])->name('companies');
    Route::get('/preciouse-stones-licenses', [AppController::class, 'pSLicenses'])->name('ps_licenses');
    Route::get('/preciouse-stones-maktoobs', [AppController::class, 'pSMaktoobs'])->name('ps_maktoobs');
    Route::get('/preciouse-stones-stones', [AppController::class, 'pSStones'])->middleware('permission:سنگ های قیمتی و نیمه قیمتی')->name('ps_stones');
    Route::get('/activity-logs', [AppController::class, 'activityLog'])->name('activity_logs');
});
