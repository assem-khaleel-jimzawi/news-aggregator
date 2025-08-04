<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;

Route::get('/articles', [ArticleController::class, 'index']);
Route::post('/articles', [ArticleController::class, 'store']);

// Specific routes must come before parameterized routes
Route::get('/articles/fetch-latest', [ArticleController::class, 'fetchLatestArticles']);
Route::get('/articles/fetch-latest/{provider}', [ArticleController::class, 'fetchLatestArticlesByProvider']);
Route::get('/articles/fetch-latest/{provider}/{category}', [ArticleController::class, 'fetchLatestArticlesByProviderAndCategory']);
Route::get('/articles/fetch-latest/{provider}/{category}/{date}', [ArticleController::class, 'fetchLatestArticlesByProviderCategoryAndDate']);
Route::get('/articles/fetch-latest/{provider}/{category}/{date}/{author}', [ArticleController::class, 'fetchLatestArticlesByProviderCategoryDateAndAuthor']);
Route::get('/articles/fetch-latest/{provider}/{category}/{date}/{author}/{source}', [ArticleController::class, 'fetchLatestArticlesByProviderCategoryDateAuthorAndSource']);
Route::get('/articles/fetch-latest/{provider}/{category}/{date}/{author}/{source}/{limit}', [ArticleController::class, 'fetchLatestArticlesByProviderCategoryDateAuthorSourceAndLimit']);
Route::get('/articles/fetch-latest/{provider}/{category}/{date}/{author}/{source}/{limit}/{offset}', [ArticleController::class, 'fetchLatestArticlesByProviderCategoryDateAuthorSourceLimitAndOffset']);
Route::get('/articles/fetch-latest/{provider}/{category}/{date}/{author}/{source}/{limit}/{offset}/{sort}', [ArticleController::class, 'fetchLatestArticlesByProviderCategoryDateAuthorSourceLimitOffsetAndSort']);
Route::get('/articles/fetch-latest/{provider}/{category}/{date}/{author}/{source}/{limit}/{offset}/{sort}/{order}', [ArticleController::class, 'fetchLatestArticlesByProviderCategoryDateAuthorSourceLimitOffsetSortAndOrder']);
Route::get('/articles/fetch-latest/{provider}/{category}/{date}/{author}/{source}/{limit}/{offset}/{sort}/{order}/{fields}', [ArticleController::class, 'fetchLatestArticlesByProviderCategoryDateAuthorSourceLimitOffsetSortOrderAndFields']);

// Parameterized routes must come after specific routes
Route::get('/articles/{id}', [ArticleController::class, 'show']);
Route::put('/articles/{id}', [ArticleController::class, 'update']);
Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);