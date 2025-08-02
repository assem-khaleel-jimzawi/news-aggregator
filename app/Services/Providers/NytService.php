<?php

namespace App\Services\Providers;

use Illuminate\Support\Facades\Http;
use App\Services\Contracts\NewsProviderInterface;

class NytService implements NewsProviderInterface
{
    public function fetchArticles(): array
    {
        $response = Http::get(config('services.nyt.url'), [
            'api-key' => config('services.nyt.key'),
        ]);

        if (!$response->successful()) {
            return [];
        }

        $results = $response->json('results');

        return collect($results)->map(function ($item) {
            return [
                'title'        => $item['title'] ?? '',
                'description'  => $item['abstract'] ?? null,
                'author'       => $item['byline'] ?? null,
                'source'       => 'New York Times',
                'category'     => $item['section'] ?? 'general',
                'url'          => $item['url'],
                'published_at' => $item['published_date'] ?? now(),
            ];
        })->toArray();
    }
}
