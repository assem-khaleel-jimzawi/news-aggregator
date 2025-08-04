<?php

namespace App\Services\Providers;

use Illuminate\Support\Facades\Http;
use App\Services\Contracts\NewsProviderInterface;

class NewsApiService implements NewsProviderInterface
{
    public function fetchArticles(): array
    {
        $response = Http::get(config('services.newsapi.url'), [
            'apiKey' => config('services.newsapi.key'),
            'country' => 'us',
            'pageSize' => 10
        ]);

        if (!$response->successful()) {
            return [];
        }

        $articles = $response->json('articles');

        return collect($articles)->map(function ($item) {
            return [
                'title'        => $item['title'] ?? '',
                'description'  => $item['description'] ?? null,
                'author'       => $item['author'] ?? null,
                'source'       => $item['source']['name'] ?? 'NewsAPI',
                'category'     => 'general',
                'url'          => $item['url'],
                'published_at' => $item['publishedAt'] ?? now(),
            ];
        })->toArray();
    }
}
