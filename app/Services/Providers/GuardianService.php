<?php

namespace App\Services\Providers;

use Illuminate\Support\Facades\Http;
use App\Services\Contracts\NewsProviderInterface;

class GuardianService implements NewsProviderInterface
{
    public function fetchArticles(): array
    {
        $response = Http::get(config('services.guardian.url'), [
            'api-key' => config('services.guardian.key'),
            'show-fields' => 'headline,trailText,byline,short-url',
            'page-size' => 10,
        ]);

        if (!$response->successful()) {
            return [];
        }

        $results = $response->json('response.results');

        return collect($results)->map(function ($item) {
            return [
                'title'        => $item['webTitle'] ?? '',
                'description'  => $item['fields']['trailText'] ?? null,
                'author'       => $item['fields']['byline'] ?? null,
                'source'       => 'The Guardian',
                'category'     => $item['sectionName'] ?? 'general',
                'url'          => $item['webUrl'],
                'published_at' => $item['webPublicationDate'] ?? now(),
            ];
        })->toArray();
    }
}
