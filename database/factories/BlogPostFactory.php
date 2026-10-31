<?php

namespace Database\Factories;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(6),
            'slug' => fake()->unique()->slug(),
            'excerpt' => fake()->paragraph(),
            'body' => fake()->paragraphs(5, true),
            'cover_image' => null,
            'cover_image_alt' => null,
            'meta_title' => null,
            'meta_description' => null,
            'status' => BlogPost::STATUS_DRAFT,
            'is_featured' => false,
            'published_at' => null,
            'user_id' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now()->subDays(fake()->numberBetween(0, 30)),
        ]);
    }
}
