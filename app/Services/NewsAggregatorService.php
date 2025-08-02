<?php
namespace App\Services;

use App\Services\Contracts\NewsProviderInterface;

class NewsAggregatorService
{
    public function __construct(private array $providers) {}

    public function fetchFromAll(): array
    {
        $allArticles = [];

        foreach ($this->providers as $provider) {
            $allArticles = array_merge($allArticles, $provider->fetchArticles());
        }

        return $allArticles;
    }
}
