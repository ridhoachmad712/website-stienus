<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = rtrim(fake()->sentence(fake()->numberBetween(5, 10)), '.');

        $content = collect(fake()->paragraphs(fake()->numberBetween(4, 7)))
            ->map(fn (string $paragraph): string => "<p>{$paragraph}</p>")
            ->implode("\n");

        $createdAt = fake()->dateTimeBetween('-6 months', 'now');

        return [
            'category_id' => Category::factory(),
            'title' => Str::title($title),
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 999999),
            'content' => $content,
            'featured_image' => null,
            'status' => fake()->randomElement(['published', 'published', 'published', 'draft']),
            'views_count' => fake()->numberBetween(0, 5000),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => 'published']);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => 'draft']);
    }
}
