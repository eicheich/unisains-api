<?php

use App\Http\Controllers\Api\V1\Client\CourseController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Client\CartController;
use App\Http\Controllers\Api\V1\Client\ProfileController;
use App\Http\Controllers\Api\V1\Client\TransactionController;
use App\Http\Controllers\Api\V1\Client\ReportController;
use App\Http\Controllers\Api\V1\Client\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/acces-denied', function () {
    return response()->json([
        'message' => 'Unauthorize',
    ], 501);
})->name('login');

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });
    // Route::prefix('admin')->middleware('isAdmin')->group(function () {
    //     Route::get('dashboard', [DashboardController::class, 'dashboard']);
    //     Route::prefix('course')->group(function () {
    //         Route::get('all', [CourseController::class, 'all']);
    //         Route::post('store', [CourseController::class, 'store']);
    //         Route::get('show/{id}', [CourseController::class, 'show']);
    //         Route::post('update/{id}', [CourseController::class, 'update']);
    //         Route::post('delete/{id}', [CourseController::class, 'delete']);
    //     });
    // });
    Route::prefix('course')->group(function () {
        Route::get('all', [CourseController::class, 'all']);
        Route::get('category', [CourseController::class, 'category']);
        Route::get('preview/{id}', [CourseController::class, 'preview']);
        Route::get('search', [CourseController::class, 'search']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('show/{id}', [CourseController::class, 'show']);
            Route::get('learn/{id}', [CourseController::class, 'learn']);
            Route::prefix('cart')->group(function () {
                Route::post('store', [CartController::class, 'store']);
                Route::get('all', [CartController::class, 'all']);
                Route::post('delete/{id}', [CartController::class, 'delete']);
            });
        });
    });
    Route::prefix('profile')->middleware('auth:sanctum')->group(function () {
        Route::get('show', [ProfileController::class, 'show']);
        Route::post('update', [ProfileController::class, 'update']);
    });
    Route::prefix('transaction')->middleware('auth:sanctum')->group(function () {
        Route::get('all', [TransactionController::class, 'all']);
        Route::post('store', [TransactionController::class, 'store']);
        Route::get('show/{id}', [TransactionController::class, 'show']);
        // Route::get('learn/{id}', [TransactionController::class, 'learn']);
        Route::post('update/{id}', [TransactionController::class, 'update']);
        Route::post('delete/{id}', [TransactionController::class, 'delete']);

        Route::post("checkout",[PaymentController::class, 'payment']);
    });
    Route::prefix('report')->group(function () {
        Route::get('all', [ReportController::class, 'all'])->middleware('isAdmin');
        Route::post('store', [ReportController::class, 'store'])->middleware('auth:sanctum');
    });
});
