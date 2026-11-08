<?php

namespace Database\Factories;

use App\Enums\ArticleStatus;
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
        return [
            'title' => fake()->unique()->sentence(5),
            'excerpt' => fake()->sentence(12),
            'body' => '<p>'.fake()->paragraph().'</p>',
            'author_name' => fake()->name(),
            'status' => ArticleStatus::Published,
            'allow_comments' => true,
            'published_at' => now()->subDay(),
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => ArticleStatus::Draft, 'published_at' => null]);
    }

    public function commentsClosed(): static
    {
        return $this->state(['allow_comments' => false]);
    }
}
