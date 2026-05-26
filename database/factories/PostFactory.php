<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = rtrim(fake()->sentence(6), '.');

        return [
            'title' => $title,
            'excerpt' => fake()->sentence(20),
            'content' => collect(range(1, 4))
                ->map(fn () => '<p>'.fake()->paragraph(8).'</p>')
                ->implode("\n"),
            'category_id' => Category::factory(),
            'author_id' => User::factory(),
            'status' => PostStatus::Published,
            'published_at' => now()->subDays(fake()->numberBetween(0, 30)),
            'meta_title' => null,
            'meta_description' => null,
            'og_image_url' => null,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => PostStatus::Draft,
            'published_at' => null,
        ]);
    }

    public function scheduled(\DateTimeInterface|CarbonInterface|null $at = null): static
    {
        return $this->state(fn () => [
            'status' => PostStatus::Scheduled,
            'published_at' => $at ?? now()->addHour(),
        ]);
    }

    public function published(\DateTimeInterface|CarbonInterface|null $at = null): static
    {
        return $this->state(fn () => [
            'status' => PostStatus::Published,
            'published_at' => $at ?? now()->subMinutes(5),
        ]);
    }
}
