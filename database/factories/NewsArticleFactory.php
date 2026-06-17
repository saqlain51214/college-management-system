<?php

namespace Database\Factories;

use App\Models\NewsArticle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NewsArticleFactory extends Factory
{
    protected $model = NewsArticle::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(5);
        return [
            'title'          => $title,
            'slug'           => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'excerpt'        => $this->faker->paragraph(2),
            'content'        => $this->faker->paragraphs(4, true),
            'category'       => 'news',
            'published_date' => now()->subDays(rand(0, 30)),
            'is_published'   => true,
            'is_featured'    => false,
            'views'          => 0,
        ];
    }

    public function unpublished(): static
    {
        return $this->state(['is_published' => false]);
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }
}
