<?php
namespace App\Services;

use App\Services\Contracts\NewsProviderInterface;
use App\Repositories\ArticleRepository;

class NewsAggregatorService
{
    public function __construct(private array $providers = []) {}

    public function fetchFromAll(): array
    {
        $allArticles = [];

        foreach ($this->providers as $provider) {
            if ($provider instanceof NewsProviderInterface) {
                $allArticles = array_merge($allArticles, $provider->fetchArticles());
            }
        }

        return $allArticles;
    }

    public function fetchLatestArticles(): array
    {
        $articles = $this->fetchFromAll();
        
        // Store articles in database
        $repository = new ArticleRepository();
        $repository->storeMany($articles);
        
        return $articles;
    }

    public function fetchFromProvider(string $providerName): array
    {
        foreach ($this->providers as $provider) {
            if (class_basename($provider) === $providerName . 'Service') {
                return $provider->fetchArticles();
            }
        }
        
        return [];
    }
}
