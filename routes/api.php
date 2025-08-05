<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;

// Basic CRUD
Route::get('/articles', [ArticleController::class, 'index']);
Route::post('/articles', [ArticleController::class, 'store']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);
Route::put('/articles/{id}', [ArticleController::class, 'update']);
Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);

// Latest Articles
Route::get('/articles/fetch-latest', [ArticleController::class, 'fetchLatestArticles']);

// Flexible Filtering (Single entry for provider, category, date, author, source, sorting, etc.)
Route::get('/articles/fetch-latest/filter', [ArticleController::class, 'fetchWithFilters']);
