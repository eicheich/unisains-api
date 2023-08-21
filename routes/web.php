<?php

use App\Http\Controllers\Web\Admin\ARController;
use App\Http\Controllers\Web\Admin\AuthController;
use App\Http\Controllers\Web\Admin\CourseController;
use App\Http\Controllers\Web\Admin\UserController;
use App\Http\Controllers\Web\Admin\DashboardController;
use App\Http\Controllers\Web\Admin\ModuleController;
use App\Http\Controllers\Web\Admin\QuizController;
use App\Http\Controllers\Web\Admin\TransactionController;
use App\Http\Controllers\Web\Admin\ReportController;
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
        Route::get('search', [CourseController::class, 'search'])->name('course.search');

        Route::prefix('modules')->group(function () {
            Route::post('store', [ModuleController::class, 'store'])->name('store.modules');
            Route::get('update-page/{id}', [ModuleController::class, 'updatePage'])->name('update.modules.page');
            Route::post('update/{id}', [ModuleController::class, 'update'])->name('update.modules');
            Route::post('delete/{id}', [ModuleController::class, 'delete'])->name('delete.modules');
        });
        Route::prefix('rangkuman')->group(function () {
            Route::get('create/{course_id}', [ModuleController::class, 'createRangkuman'])->name('create.rangkuman');
            Route::post('store', [ModuleController::class, 'storeRangkuman'])->name('store.rangkuman');
            Route::get('edit/{id}', [ModuleController::class, 'editRangkuman'])->name('update.rangkuman.page');
            Route::post('update/{id}', [ModuleController::class, 'updateRangkuman'])->name('update.rangkuman');
            Route::post('delete/{id}', [ModuleController::class, 'deleteRangkuman'])->name('delete.rangkuman');
        });
        Route::prefix('ar')->group(function () {
            Route::post('store', [ARController::class, 'store'])->name('store.ar');
            Route::get('edit/{id}', [ARController::class, 'edit'])->name('edit.ar.page');
            Route::post('update/{id}', [ARController::class, 'update'])->name('update.ar');
            Route::post('delete/{id}', [ARController::class, 'delete'])->name('delete.ar');
        });
        Route::prefix('quiz')->group(function () {
            Route::post('store', [QuizController::class, 'store'])->name('store.quiz');
            Route::get('edit/{id}', [QuizController::class, 'edit'])->name('update.quiz.page');
            Route::get('show/{id}', [QuizController::class, 'show'])->name('quiz.show');
            Route::post('update/{id}', [QuizController::class, 'update'])->name('update.quiz');
            Route::post('delete/{id}', [QuizController::class, 'delete'])->name('delete.quiz');
        });
    });
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'all'])->name('users.page');
        Route::post('store', [AuthController::class, 'store'])->name('store.users');
        Route::get('show/{id}', [AuthController::class, 'show'])->name('users.show');
        Route::get('update-page/{id}', [AuthController::class, 'updatePage'])->name('update.users.page');
        Route::post('update/{id}', [AuthController::class, 'update'])->name('update.users');
        Route::post('delete/{id}', [UserController::class, 'delete'])->name('delete.users');
        Route::get('search', [UserController::class, 'search'])->name('users.search');
    });
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'all'])->name('transactions.page');
        Route::post('store', [TransactionController::class, 'store'])->name('store.transactions');
        Route::get('show/{id}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('update-page/{id}', [TransactionController::class, 'updatePage'])->name('update.transactions.page');
        Route::post('update/{id}', [TransactionController::class, 'update'])->name('update.transactions');
        Route::post('delete/{id}', [TransactionController::class, 'delete'])->name('delete.transactions');
        Route::get('search', [TransactionController::class, 'search'])->name('transactions.search');
    });

    Route::post('delete/{id}', [AuthController::class, 'delete'])->name('delete.users');

    Route::prefix('report')->group(function () {
        Route::get('/', [ReportController::class, 'all'])->name('report.page');
        Route::get('show/{id}', [ReportController::class, 'showReport'])->name('report.show');
        Route::get('update-page/{id}', [ReportController::class, 'updatePageReport'])->name('update.report.page');
        Route::post('update/{id}', [ReportController::class, 'updateReport'])->name('update.report');
        Route::post('delete/{id}', [ReportController::class, 'deleteReport'])->name('delete.report');
    });
});
