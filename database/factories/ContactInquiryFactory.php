<?php

namespace Database\Factories;

use App\Enums\InquirySource;
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
            'is_veteran' => false,
            'source' => InquirySource::Contact,
            'message' => fake()->paragraph(),
            'ip_address' => fake()->ipv4(),
            'is_spam' => false,
        ];
    }

    public function veteran(): static
    {
        return $this->state(fn () => [
            'is_veteran' => true,
        ]);
    }

    public function spam(): static
    {
        return $this->state(fn () => [
            'is_spam' => true,
        ]);
    }

    public function seoAssessment(): static
    {
        return $this->state(fn () => [
            'source' => InquirySource::SeoAssessment,
            'business_name' => fake()->company(),
            'website' => fake()->url(),
            'phone' => null,
            'plan' => null,
            'message' => null,
        ]);
    }
}
