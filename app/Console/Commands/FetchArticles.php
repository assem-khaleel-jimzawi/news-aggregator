<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsAggregatorService;
use App\Repositories\ArticleRepository;

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
            
            $this->info('Articles updated successfully!');
            $this->info('Total articles fetched: ' . count($articles));
            
        } catch (\Exception $e) {
            $this->error('Error fetching articles: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
