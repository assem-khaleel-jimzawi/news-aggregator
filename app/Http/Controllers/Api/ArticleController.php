<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{FilterArticlesRequest, StoreArticleRequest, UpdateArticleRequest};
use App\Models\Article;
use App\Repositories\ArticleRepository;
use App\Services\NewsAggregatorService;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    public function __construct(
        protected NewsAggregatorService $newsAggregatorService,
        protected ArticleRepository $articleRepository
    ) {}

    public function index(FilterArticlesRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $articles = $this->articleRepository->filter($filters);

        return response()->json($articles);
    }

    public function show(int $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        return response()->json($article);
    }

    public function store(StoreArticleRequest $request): JsonResponse
    {
        $data = $request->validated();
        $article = Article::create($data);

        return response()->json($article, 201);
    }

    public function update(UpdateArticleRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $article = Article::findOrFail($id);
        $article->update($data);

        return response()->json($article);
    }

    public function destroy(int $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(null, 204);
    }

    public function fetchLatestArticles(): JsonResponse
    {
        $articles = $this->newsAggregatorService->fetchLatestArticles();

        return response()->json([
            'message' => 'Articles fetched and stored successfully',
            'count' => count($articles),
            'articles' => $articles,
        ]);
    }

    public function fetchWithFilters(FilterArticlesRequest $request): JsonResponse
    {
        $filters = $request->validated();

        $provider = $filters['provider'] ?? null;
        $articles = $provider
            ? $this->newsAggregatorService->fetchFromProvider($provider)
            : $this->newsAggregatorService->fetchFromAll();

        // Filtering logic
        $filteredArticles = collect($articles)->filter(function ($article) use ($filters) {
            foreach (['category', 'author', 'source', 'date'] as $field) {
                if (!empty($filters[$field]) && strcasecmp($article[$field] ?? '', $filters[$field]) !== 0) {
                    return false;
                }
            }

            if (!empty($filters['date']) && !str_contains($article['published_at'] ?? '', $filters['date'])) {
                return false;
            }

            return true;
        });

        // Sorting
        if (!empty($filters['sort'])) {
            $filteredArticles = $filters['order'] === 'desc'
                ? $filteredArticles->sortByDesc($filters['sort'])
                : $filteredArticles->sortBy($filters['sort']);
        }

        // Offset & limit
        $filteredArticles = $filteredArticles
            ->skip($filters['offset'] ?? 0)
            ->take($filters['limit'] ?? 10)
            ->values();

        // Select fields
        if (!empty($filters['fields'])) {
            $fields = explode(',', $filters['fields']);
            $filteredArticles = $filteredArticles->map(fn ($a) => collect($a)->only($fields));
        }

        return response()->json([
            'count' => $filteredArticles->count(),
            'articles' => $filteredArticles->toArray(),
        ]);
    }
}