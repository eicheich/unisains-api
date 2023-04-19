<?php

use App\Http\Controllers\Api\V1\Admin\DashboardController;
use App\Http\Controllers\Api\V1\AuthController;
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
        Route::get('login', [AuthController::class, 'login'])->name('login.get');
        Route::post('register', [AuthController::class, 'register']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
            Route::post('update-profile', [AuthController::class, 'updateProfile']);
        });
    });
    Route::prefix('admin')->middleware('isAdmin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'dashboard']);
        
    });
});
