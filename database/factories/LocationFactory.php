<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Location>
 */
class LocationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $city = fake()->unique()->city();

        return [
            'name' => $city,
            'display_name' => $city.', FL',
            'region' => fake()->randomElement(['Emerald Coast', 'Bay County', '30A']),
            'tagline' => 'Web Design & SEO',
            'hero_subhead' => fake()->sentence(12),
            'intro' => fake()->paragraph(4),
            'why_local' => fake()->paragraph(4),
            'body' => '<p>'.fake()->paragraph(8).'</p>',
            'segments' => [
                ['title' => fake()->words(3, true), 'body' => fake()->sentence(14)],
                ['title' => fake()->words(3, true), 'body' => fake()->sentence(14)],
            ],
            'faqs' => [
                ['question' => fake()->sentence().'?', 'answer' => fake()->sentence(14)],
            ],
            'lat' => fake()->latitude(30, 31),
            'lng' => fake()->longitude(-87, -85),
            'meta_title' => null,
            'meta_description' => fake()->sentence(16),
            'og_image_url' => null,
            'sort_order' => fake()->numberBetween(0, 20),
            'is_published' => true,
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn (): array => ['is_published' => false]);
    }
}
