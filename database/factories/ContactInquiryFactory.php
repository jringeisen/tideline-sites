<?php

namespace Database\Factories;

use App\Models\ContactInquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactInquiry>
 */
class ContactInquiryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'plan' => fake()->randomElement(['essential', 'growth', 'unsure']),
            'source' => ContactInquiry::SOURCE_CONTACT,
            'message' => fake()->paragraph(),
        ];
    }

    public function seoAssessment(): static
    {
        return $this->state(fn () => [
            'source' => ContactInquiry::SOURCE_SEO_ASSESSMENT,
            'business_name' => fake()->company(),
            'website' => fake()->url(),
            'phone' => null,
            'plan' => null,
            'message' => null,
        ]);
    }
}
