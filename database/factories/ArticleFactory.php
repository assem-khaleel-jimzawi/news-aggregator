<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $sources = ['NewsAPI', 'The Guardian', 'New York Times', 'BBC News'];
        $categories = ['technology', 'politics', 'business', 'sports', 'entertainment', 'science', 'health'];

        return [
            'title' => $this->faker->sentence()<?php

            return [
            
                /*
                |--------------------------------------------------------------------------
                | Third Party Services
                |--------------------------------------------------------------------------
                |
                | This file is for storing the credentials for third party services such
                | as Mailgun, Postmark, AWS and more. This file provides the de facto
                | location for this type of information, allowing packages to have
                | a conventional file to locate the various service credentials.
                |
                */
            
                'postmark' => [
                    'token' => env('POSTMARK_TOKEN'),
                ],
            
                'resend' => [
                    'key' => env('RESEND_KEY'),
                ],
            
                'ses' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
                ],
            
                'slack' => [
                    'notifications' => [
                        'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
                        'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
                    ],
                ],
            
                'newsapi' => [
                    'key' => env('NEWSAPI_KEY'),
                    'url' => env('NEWSAPI_URL', 'https://newsapi.org/v2/top-headlines'),
                ],
            
                'guardian' => [
                    'key' => env('GUARDIAN_KEY'),
                    'url' => env('GUARDIAN_URL', 'https://content.guardianapis.com/search'),
                ],
            
                'nyt' => [
                    'key' => env('NYT_KEY'),
                    'url' => env('NYT_URL', 'https://api.nytimes.com/svc/news/v3/content/all/all.json'),
                ],
            
            ];
            ,
            'description' => $this->faker->paragraph(),
            'author' => $this->faker->name(),
            'source' => $this->faker->randomElement($sources),
            'category' => $this->faker->randomElement($categories),
            'url' => $this->faker->unique()->url(),
            'published_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }

    public function fromSource(string $source): static
    {
        return $this->state(fn () => ['source' => $source]);
    }

    public function inCategory(string $category): static
    {
        return $this->state(fn () => ['category' => $category]);
    }

    public function byAuthor(string $author): static
    {
        return $this->state(fn () => ['author' => $author]);
    }
}
