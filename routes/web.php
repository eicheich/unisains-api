<?php

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

Route::prefix('admin')->middleware('isAdmin')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard']);
    Route::prefix('course')->group(function () {
        Route::get('all', [CourseController::class, 'all']);
        Route::post('store', [CourseController::class, 'store']);
        Route::get('show/{id}', [CourseController::class, 'show']);
        Route::post('update/{id}', [CourseController::class, 'update']);
        Route::post('delete/{id}', [CourseController::class, 'delete']);
    });
});
