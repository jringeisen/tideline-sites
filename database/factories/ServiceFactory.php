<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = rtrim(fake()->unique()->words(2, true), '.');

        return [
            'name' => ucfirst($name),
            'summary' => fake()->sentence(12),
            'icon' => null,
            'hero_subhead' => fake()->sentence(10),
            'body' => '<p>'.fake()->paragraph(8).'</p>',
            'faqs' => [
                ['question' => fake()->sentence().'?', 'answer' => fake()->sentence(14)],
            ],
            'meta_title' => null,
            'meta_description' => null,
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
