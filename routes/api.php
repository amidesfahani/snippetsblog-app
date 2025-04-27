<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SnippetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::prefix('snippets')->group(function () {
    Route::get('/', [SnippetController::class, 'index']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);
    Route::post('auth/logout', [AuthController::class, 'logout']);

    Route::prefix('snippets')->group(function () {
        Route::post('/', [SnippetController::class, 'store']);

        Route::post('/{id}/comments', [SnippetController::class, 'storeComment']);
        Route::post('/{id}/like', [SnippetController::class, 'storeLike']);
    });
});