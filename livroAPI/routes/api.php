<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::post('/books', [BookController::class, 'store']);
Route::get('/books/{id}/pdf', [BookController::class, 'showPdf']);

