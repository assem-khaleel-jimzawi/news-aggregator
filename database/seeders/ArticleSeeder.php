<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create bulk articles for general testing
        Article::factory(50)->create();

        // Create specific articles
        Article::factory()->create([
            'title' => 'Breaking News: Major Technology Breakthrough',
            'description' => 'Scientists discover revolutionary new technology that could change the world.',
            'author' => 'John Doe',
            'source' => 'NewsAPI',
            'category' => 'technology',
            'url' => 'https://innoscripta/tech-breakthrough',
            'published_at' => now(),
        ]);

        Article::factory()->create([
            'title' => 'Political Update: New Policy Announced',
            'description' => 'Government announces new policy that affects millions of citizens.',
            'author' => 'Jane Smith',
            'source' => 'The Guardian',
            'category' => 'politics',
            'url' => 'https://innoscripta/political-update',
            'published_at' => now()->subDay(),
        ]);

        Article::factory()->create([
            'title' => 'Business News: Market Analysis',
            'description' => 'Comprehensive analysis of current market trends and predictions.',
            'author' => 'Mike Johnson',
            'source' => 'New York Times',
            'category' => 'business',
            'url' => 'https://innoscripta/business-news',
            'published_at' => now()->subDays(2),
        ]);
    }
}
