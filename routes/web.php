<?php

use App\Http\Controllers\Web\Admin\AuthController;
use App\Http\Controllers\Web\Admin\CourseController;
use App\Http\Controllers\Web\Admin\DashboardController;
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

Route::get('/', function () {
    return view('layouts.main');
});

Route::prefix('auth')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login.page');
    Route::post('login', [AuthController::class, 'loginPost'])->name('login.post');
    Route::post('register', [AuthController::class, 'register']);
    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout.post');
        Route::get('me', [AuthController::class, 'me']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
    });
});

Route::prefix('admin')->middleware('isAdmin')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard.page');
    Route::prefix('course')->group(function () {
        Route::get('all', [CourseController::class, 'all']);
        Route::post('store', [CourseController::class, 'store']);
        Route::get('show/{id}', [CourseController::class, 'show']);
        Route::post('update/{id}', [CourseController::class, 'update']);
        Route::post('delete/{id}', [CourseController::class, 'delete']);
    });
});