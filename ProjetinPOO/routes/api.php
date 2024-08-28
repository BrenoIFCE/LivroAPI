<?php

use App\Http\Controllers\VerificationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;




Route::get('/users', [UserController::class, 'index']);
Route::post('/register', [UserController::class, 'store']);
Route::put('/users/{user}', [UserController::class, 'update']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);
Route::put('confirm/email/{id}/{token}', [VerificationController::class, 'verifyEmail'])->name('confirm.email');

Route::post('/books', [BookController::class, 'store']);
Route::get('/books/{id}/pdf', [BookController::class, 'downloadPdf']);
Route::get('/books', [BookController::class, 'index']);
Route::post('/books/{book}/comments', [BookController::class, 'addComment'])->middleware('auth:sanctum');


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});
