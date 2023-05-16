<?php

use App\Http\Controllers\Web\Admin\AuthController;
use App\Http\Controllers\Web\Admin\CourseController;
use App\Http\Controllers\Web\Admin\DashboardController;
use App\Http\Controllers\Web\Admin\ModuleController;
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
    return redirect()->route('dashboard.page');
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

Route::prefix('admin')->middleware('isAdminWeb')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard.page');
    Route::prefix('course')->group(function () {
        Route::get('/', [CourseController::class, 'all'])->name('course.page');
        Route::get('add', [CourseController::class, 'add'])->name('add.course');
        Route::post('store', [CourseController::class, 'store'])->name('store.course');
        Route::get('show/{id}', [CourseController::class, 'show'])->name('course.show');
        Route::get('update-page/{id}', [CourseController::class, 'updatePage'])->name('update.course.page');
        Route::post('update/{id}', [CourseController::class, 'update'])->name('update.course');
        Route::post('delete/{id}', [CourseController::class, 'delete'])->name('delete.course');

        Route::prefix('modules')->group(function () {
            Route::post('store', [ModuleController::class, 'store'])->name('store.modules');
            Route::get('update-page/{id}', [ModuleController::class, 'updatePage'])->name('update.modules.page');
            Route::post('update/{id}', [ModuleController::class, 'update'])->name('update.modules');
            Route::post('delete/{id}', [ModuleController::class, 'delete'])->name('delete.modules');
        });
    });
});
