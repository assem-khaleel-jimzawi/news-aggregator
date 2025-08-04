<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsAggregatorService;
use App\Repositories\ArticleRepository;
use Illuminate\Support\Facades\Log;

/**
 * Class FetchArticles
 *
 * This command fetches the latest articles from various news providers
 * and stores them in the database.
 */
class FetchArticles extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetch latest articles from news providers and store them in the database';

    public function __construct(
        private NewsAggregatorService $aggregator,
        private ArticleRepository $repository
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fetch articles from news providers...');

        try {
            $articles = $this->aggregator->fetchLatestArticles();

            if (empty($articles)) {
                $this->warn('No articles were fetched from providers.');
                return 0;
            }

            $results = $this->repository->storeMany($articles);

            $this->info('Articles processed successfully!');
            $this->info('Total fetched: ' . $results['fetched']);
            $this->info('Newly saved: ' . $results['saved']);
            $this->info('Duplicates skipped: ' . $results['skipped']);

            if ($results['errors']) {
                $this->error('Errors occurred during saving: ' . implode(', ', $results['errors']));
            }

        } catch (\Exception $e) {
            $this->error('Error fetching or saving articles: ' . $e->getMessage());
            Log::error('News Fetch Command Failed', [
                'exception' => $e
            ]);
            return 1;
        }

        return 0;
    }
}