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
            'title' => $this->faker->sentence(),
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
