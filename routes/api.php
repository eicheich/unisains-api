<?php

use App\Http\Controllers\Api\V1\Client\CourseController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Client\CartController;
use App\Http\Controllers\Api\V1\Client\ProfileController;
use App\Http\Controllers\Api\V1\EmailVerifController;
use App\Http\Controllers\Api\V1\Client\ReportController;
use App\Http\Controllers\Api\V1\Client\PaymentController;
use App\Http\Controllers\Api\V1\Client\TransactionController;
use App\Http\Controllers\Api\V1\Client\WishlistController;
use App\Http\Controllers\Api\V1\Client\RateController;
use App\Http\Controllers\Api\V1\TeacherController;
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
        route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('verif-email', [EmailVerifController::class, 'verifEmail']);
            Route::post('reset-password', [AuthController::class, 'resetPassword']);
        });
    });
    Route::post('callback', [PaymentController::class, 'callback']);

    Route::post('generate',[TeacherController::class, 'generate']);
    Route::post('testemail',[PaymentController::class, 'test'])->middleware('auth:sanctum');
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

            Route::get('learn/{id}', [CourseController::class, 'learn'])->middleware('isBought');
            Route::get('show/{id}', [CourseController::class, 'show']);
            Route::prefix('cart')->group(function () {
                Route::post('store', [CartController::class, 'store']);
                Route::get('all', [CartController::class, 'all']);
                Route::delete('delete/{id}', [CartController::class, 'delete']);
            });
            Route::prefix('wishlist')->group(function () {
                Route::post('store', [WishlistController::class, 'store']);
                Route::get('all', [WishlistController::class, 'all']);
                Route::delete('delete/{id}', [WishlistController::class, 'delete']);
            });
            Route::post('trx-quiz/{id}', [TransactionController::class, 'quiz']);
            Route::post('rate', [RateController::class, 'rate']);
            Route::get('rate/edit/{id}', [RateController::class, 'editRate']);
            Route::post('rate/update/{id}', [RateController::class, 'updateRate']);
        });
    });
    Route::prefix('profile')->middleware('auth:sanctum')->group(function () {
        Route::get('cart-count', [CartController::class, 'cartCount']);
        Route::get('show', [ProfileController::class, 'show']);
        Route::post('update', [ProfileController::class, 'update']);
    });
    Route::prefix('transaction')->middleware('auth:sanctum')->group(function () {
        Route::get('all', [TransactionController::class, 'all']);
        Route::post('store', [TransactionController::class, 'store']);
        Route::get('show/{id}', [TransactionController::class, 'show']);
        Route::post('update/{id}', [TransactionController::class, 'update']);
        Route::post('delete/{id}', [TransactionController::class, 'delete']);
//        checkout
        Route::post('checkout', [PaymentController::class, 'payment']);
    });
//    report
    Route::prefix('report')->middleware('auth:sanctum')->group(function () {
        Route::post('store', [ReportController::class, 'store']);
        Route::get('all', [ReportController::class, 'all']);
    });
    Route::prefix('teacher')->group(function (){
        Route::post('login', [TeacherController::class, 'login']);
        Route::middleware('isTeacher')->group(function (){
            Route::middleware('auth:sanctum')->group(function (){
            Route::post('logout', [TeacherController::class, 'logout']);
            Route::prefix('course')->group(function (){
                Route::get('all', [CourseController::class, 'all']);
                Route::get('show/{id}', [TeacherController::class, 'show']);
                Route::post('update/{id}', [TeacherController::class, 'update']);
            });
            Route::get('dashboard', [TeacherController::class, 'dashboard']);
            });

        });
    });
});
