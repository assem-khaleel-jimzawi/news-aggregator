<?php
namespace App\Repositories;

use App\Models\Article;

class ArticleRepository
{
    public function storeMany(array $articles): void
    {
        foreach ($articles as $data) {
            Article::updateOrCreate(
                ['url' => $data['url']],
                $data
            );
        }
    }

    public function filter(array $filters)
    {
        return Article::query()
            ->when($filters['author'] ?? null, fn($q, $v) => $q->where('author', $v))
            ->when($filters['source'] ?? null, fn($q, $v) => $q->where('source', $v))
            ->when($filters['category'] ?? null, fn($q, $v) => $q->where('category', $v))
            ->when($filters['date'] ?? null, fn($q, $v) => $q->whereDate('published_at', $v))
            ->latest('published_at')
            ->paginate(10);
    }
}
