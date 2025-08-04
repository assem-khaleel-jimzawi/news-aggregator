<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Article;
use App\Repositories\ArticleRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected ArticleRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ArticleRepository();
    }

    public function test_it_stores_articles_in_database()
    {
        $data = [
            [
                'title' => 'Test Article',
                'description' => 'Lorem ipsum',
                'author' => 'John',
                'source' => 'NewsAPI',
                'category' => 'general',
                'url' => 'https://innoscripta.com/article1',
                'published_at' => now(),
            ]
        ];

        $this->repository->storeMany($data);

        $this->assertDatabaseHas('articles', ['url' => 'https://innoscripta.com/article1']);
    }

    public function test_it_updates_existing_article_by_url()
    {
        Article::factory()->create([
            'url' => 'https://innoscripta.com/article1',
            'title' => 'Old Title',
        ]);

        $data = [
            [
                'title' => 'New Title',
                'description' => 'Updated',
                'author' => 'John',
                'source' => 'NewsAPI',
                'category' => 'general',
                'url' => 'https://innoscripta.com/article1',
                'published_at' => now(),
            ]
        ];

        $this->repository->storeMany($data);

        $this->assertDatabaseHas('articles', [
            'url' => 'https://innoscripta.com/article1',
            'title' => 'New Title',
        ]);
    }

    public function test_it_does_not_duplicate_articles_with_same_url()
    {
        $data = [
            [
                'title' => 'Unique Title',
                'description' => 'A',
                'author' => 'Author',
                'source' => 'NewsAPI',
                'category' => 'general',
                'url' => 'https://innoscripta.com/unique-article',
                'published_at' => now(),
            ]
        ];

        $this->repository->storeMany($data);
        $this->repository->storeMany($data);

        $this->assertEquals(1, Article::where('url', 'https://innoscripta.com/unique-article')->count());
    }
}
