<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterArticlesRequest;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Repositories\ArticleRepository;
use App\Services\NewsAggregatorService;

// This controller handles CRUD operations for articles and integrates with the NewsAggregatorService
// to fetch the latest articles from various news providers.
// It uses the ArticleRepository for data access and applies validation through custom request classes.

class ArticleController extends Controller
{
    public function index(FilterArticlesRequest $request)
    {
        $filters = $request->validated();
        $articles = (new ArticleRepository())->filter($filters);

        return response()->json($articles);
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);
        return response()->json($article);
    }

    public function store(StoreArticleRequest $request)
    {
        $data = $request->validated();
        $article = Article::create($data);

        return response()->json($article, 201);
    }

    public function update(UpdateArticleRequest $request, $id)
    {
        $data = $request->validated();
        $article = Article::findOrFail($id);
        $article->update($data);

        return response()->json($article);
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(null, 204); 
    }

    public function fetchLatestArticles()
    {
        $newsAggregatorService = app(NewsAggregatorService::class);
        $articles = $newsAggregatorService->fetchLatestArticles();

        return response()->json([
            'message' => 'Articles fetched and stored successfully',
            'count' => count($articles),
            'articles' => $articles
        ]);
    }

    public function fetchLatestArticlesByProvider($provider)
    {
        $newsAggregatorService = app(NewsAggregatorService::class);
        $articles = $newsAggregatorService->fetchFromProvider($provider);

        return response()->json([
            'provider' => $provider,
            'count' => count($articles),
            'articles' => $articles
        ]);
    }

    public function fetchLatestArticlesByProviderAndCategory($provider, $category)
    {
        $newsAggregatorService = app(NewsAggregatorService::class);
        $articles = $newsAggregatorService->fetchFromProvider($provider);
        
        // Filter by category
        $filteredArticles = collect($articles)->filter(function ($article) use ($category) {
            return strtolower($article['category'] ?? '') === strtolower($category);
        })->values()->toArray();

        return response()->json([
            'provider' => $provider,
            'category' => $category,
            'count' => count($filteredArticles),
            'articles' => $filteredArticles
        ]);
    }

    public function fetchLatestArticlesByProviderCategoryAndDate($provider, $category, $date)
    {
        $newsAggregatorService = app(NewsAggregatorService::class);
        $articles = $newsAggregatorService->fetchFromProvider($provider);
        
        // Filter by category and date
        $filteredArticles = collect($articles)->filter(function ($article) use ($category, $date) {
            $articleDate = $article['published_at'] ?? '';
            return strtolower($article['category'] ?? '') === strtolower($category) &&
                   str_contains($articleDate, $date);
        })->values()->toArray();

        return response()->json([
            'provider' => $provider,
            'category' => $category,
            'date' => $date,
            'count' => count($filteredArticles),
            'articles' => $filteredArticles
        ]);
    }

    public function fetchLatestArticlesByProviderCategoryDateAndAuthor($provider, $category, $date, $author)
    {
        $newsAggregatorService = app(NewsAggregatorService::class);
        $articles = $newsAggregatorService->fetchFromProvider($provider);
        
        // Filter by category, date, and author
        $filteredArticles = collect($articles)->filter(function ($article) use ($category, $date, $author) {
            $articleDate = $article['published_at'] ?? '';
            return strtolower($article['category'] ?? '') === strtolower($category) &&
                   str_contains($articleDate, $date) &&
                   strtolower($article['author'] ?? '') === strtolower($author);
        })->values()->toArray();

        return response()->json([
            'provider' => $provider,
            'category' => $category,
            'date' => $date,
            'author' => $author,
            'count' => count($filteredArticles),
            'articles' => $filteredArticles
        ]);
    }

    public function fetchLatestArticlesByProviderCategoryDateAuthorAndSource($provider, $category, $date, $author, $source)
    {
        $newsAggregatorService = app(NewsAggregatorService::class);
        $articles = $newsAggregatorService->fetchFromProvider($provider);
        
        // Filter by all criteria
        $filteredArticles = collect($articles)->filter(function ($article) use ($category, $date, $author, $source) {
            $articleDate = $article['published_at'] ?? '';
            return strtolower($article['category'] ?? '') === strtolower($category) &&
                   str_contains($articleDate, $date) &&
                   strtolower($article['author'] ?? '') === strtolower($author) &&
                   strtolower($article['source'] ?? '') === strtolower($source);
        })->values()->toArray();

        return response()->json([
            'provider' => $provider,
            'category' => $category,
            'date' => $date,
            'author' => $author,
            'source' => $source,
            'count' => count($filteredArticles),
            'articles' => $filteredArticles
        ]);
    }

    public function fetchLatestArticlesByProviderCategoryDateAuthorSourceAndLimit($provider, $category, $date, $author, $source, $limit)
    {
        $newsAggregatorService = app(NewsAggregatorService::class);
        $articles = $newsAggregatorService->fetchFromProvider($provider);
        
        // Filter and limit
        $filteredArticles = collect($articles)->filter(function ($article) use ($category, $date, $author, $source) {
            $articleDate = $article['published_at'] ?? '';
            return strtolower($article['category'] ?? '') === strtolower($category) &&
                   str_contains($articleDate, $date) &&
                   strtolower($article['author'] ?? '') === strtolower($author) &&
                   strtolower($article['source'] ?? '') === strtolower($source);
        })->take($limit)->values()->toArray();

        return response()->json([
            'provider' => $provider,
            'category' => $category,
            'date' => $date,
            'author' => $author,
            'source' => $source,
            'limit' => $limit,
            'count' => count($filteredArticles),
            'articles' => $filteredArticles
        ]);
    }

    public function fetchLatestArticlesByProviderCategoryDateAuthorSourceLimitOffsetAndSort($provider, $category, $date, $author, $source, $limit, $offset, $sort)
    {
        $newsAggregatorService = app(NewsAggregatorService::class);
        $articles = $newsAggregatorService->fetchFromProvider($provider);
        
        // Filter, sort, offset, and limit
        $filteredArticles = collect($articles)->filter(function ($article) use ($category, $date, $author, $source) {
            $articleDate = $article['published_at'] ?? '';
            return strtolower($article['category'] ?? '') === strtolower($category) &&
                   str_contains($articleDate, $date) &&
                   strtolower($article['author'] ?? '') === strtolower($author) &&
                   strtolower($article['source'] ?? '') === strtolower($source);
        })->sortBy($sort)->skip($offset)->take($limit)->values()->toArray();

        return response()->json([
            'provider' => $provider,
            'category' => $category,
            'date' => $date,
            'author' => $author,
            'source' => $source,
            'limit' => $limit,
            'offset' => $offset,
            'sort' => $sort,
            'count' => count($filteredArticles),
            'articles' => $filteredArticles
        ]);
    }

    public function fetchLatestArticlesByProviderCategoryDateAuthorSourceLimitOffsetSortAndOrder($provider, $category, $date, $author, $source, $limit, $offset, $sort, $order)
    {
        $newsAggregatorService = app(NewsAggregatorService::class);
        $articles = $newsAggregatorService->fetchFromProvider($provider);
        
        // Filter, sort with order, offset, and limit
        $filteredArticles = collect($articles)->filter(function ($article) use ($category, $date, $author, $source) {
            $articleDate = $article['published_at'] ?? '';
            return strtolower($article['category'] ?? '') === strtolower($category) &&
                   str_contains($articleDate, $date) &&
                   strtolower($article['author'] ?? '') === strtolower($author) &&
                   strtolower($article['source'] ?? '') === strtolower($source);
        });

        if ($order === 'desc') {
            $filteredArticles = $filteredArticles->sortByDesc($sort);
        } else {
            $filteredArticles = $filteredArticles->sortBy($sort);
        }

        $filteredArticles = $filteredArticles->skip($offset)->take($limit)->values()->toArray();

        return response()->json([
            'provider' => $provider,
            'category' => $category,
            'date' => $date,
            'author' => $author,
            'source' => $source,
            'limit' => $limit,
            'offset' => $offset,
            'sort' => $sort,
            'order' => $order,
            'count' => count($filteredArticles),
            'articles' => $filteredArticles
        ]);
    }

    public function fetchLatestArticlesByProviderCategoryDateAuthorSourceLimitOffsetSortOrderAndFields($provider, $category, $date, $author, $source, $limit, $offset, $sort, $order, $fields)
    {
        $newsAggregatorService = app(NewsAggregatorService::class);
        $articles = $newsAggregatorService->fetchFromProvider($provider);
        
        // Filter, sort with order, offset, limit, and select fields
        $filteredArticles = collect($articles)->filter(function ($article) use ($category, $date, $author, $source) {
            $articleDate = $article['published_at'] ?? '';
            return strtolower($article['category'] ?? '') === strtolower($category) &&
                   str_contains($articleDate, $date) &&
                   strtolower($article['author'] ?? '') === strtolower($author) &&
                   strtolower($article['source'] ?? '') === strtolower($source);
        });

        if ($order === 'desc') {
            $filteredArticles = $filteredArticles->sortByDesc($sort);
        } else {
            $filteredArticles = $filteredArticles->sortBy($sort);
        }

        $filteredArticles = $filteredArticles->skip($offset)->take($limit)->values();

        // Select only specified fields
        $fieldArray = explode(',', $fields);
        $filteredArticles = $filteredArticles->map(function ($article) use ($fieldArray) {
            return collect($article)->only($fieldArray)->toArray();
        })->toArray();

        return response()->json([
            'provider' => $provider,
            'category' => $category,
            'date' => $date,
            'author' => $author,
            'source' => $source,
            'limit' => $limit,
            'offset' => $offset,
            'sort' => $sort,
            'order' => $order,
            'fields' => $fields,
            'count' => count($filteredArticles),
            'articles' => $filteredArticles
        ]);
    }
}   