<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Article::factory()->create([
            'author' => 'John Doe',
            'source' => 'NewsAPI',
            'category' => 'technology',
            'published_at' => now(),
        ]);

        Article::factory()->create([
            'author' => 'Jane Smith',
            'source' => 'The Guardian',
            'category' => 'politics',
            'published_at' => now()->subDay(),
        ]);
    }

    public function test_it_returns_articles_with_valid_filters()
    {
        $response = $this->getJson('/api/articles?author=John Doe');

        $response->assertStatus(200)
                 ->assertJsonFragment(['author' => 'John Doe']);
    }

    public function test_it_returns_empty_with_invalid_filters()
    {
        $response = $this->getJson('/api/articles?author=InvalidName');

        $response->assertStatus(200)
                 ->assertJsonMissing(['author' => 'John Doe']);
    }

    public function test_it_validates_date_format()
    {
        $response = $this->getJson('/api/articles?date=not-a-date');

        $response->assertStatus(422);
    }
}