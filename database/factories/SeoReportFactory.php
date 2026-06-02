<?php

namespace Database\Factories;

use App\Enums\SeoReportStatus;
use App\Models\SeoReport;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SeoReport>
 */
class SeoReportFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $url = fake()->url();

        return [
            'token' => (string) Str::ulid(),
            'url' => $url,
            'host' => parse_url($url, PHP_URL_HOST) ?: 'example.com',
            'industry' => fake()->randomElement(config('seo-report.industries')),
            'status' => SeoReportStatus::Pending,
            'score' => null,
            'report' => null,
            'raw_signals' => null,
            'email' => null,
            'email_captured_at' => null,
            'contact_inquiry_id' => null,
            'ip_address' => fake()->ipv4(),
            'error' => null,
        ];
    }

    public function processing(): static
    {
        return $this->state(fn () => [
            'status' => SeoReportStatus::Processing,
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn () => [
            'status' => SeoReportStatus::Failed,
            'error' => 'We could not reach that website.',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => SeoReportStatus::Completed,
            'score' => fake()->numberBetween(35, 92),
            'raw_signals' => [
                'title' => fake()->sentence(),
                'meta_description' => fake()->sentence(),
                'h1_count' => 1,
                'image_alt_coverage' => 0.6,
            ],
            'report' => self::sampleReport(),
        ]);
    }

    public function unlocked(): static
    {
        return $this->completed()->state(fn () => [
            'email' => fake()->safeEmail(),
            'email_captured_at' => now(),
        ]);
    }

    /**
     * A realistic structured report payload matching the agent schema.
     *
     * @return array<string, mixed>
     */
    public static function sampleReport(): array
    {
        return [
            'score' => 72,
            'summary' => 'Your site has a solid foundation but is missing key on-page and local signals.',
            'sections' => [
                [
                    'title' => 'On-page basics',
                    'items' => [
                        [
                            'title' => 'Tighten your title tag',
                            'priority' => 'high',
                            'category' => 'on_page',
                            'detail' => 'Your homepage title is generic. Add your primary service and city.',
                        ],
                        [
                            'title' => 'Add a meta description',
                            'priority' => 'medium',
                            'category' => 'on_page',
                            'detail' => 'No meta description was found. Write a 150-character summary.',
                        ],
                    ],
                ],
                [
                    'title' => 'Technical health',
                    'items' => [
                        [
                            'title' => 'Add alt text to images',
                            'priority' => 'medium',
                            'category' => 'technical',
                            'detail' => 'Only 60% of your images have descriptive alt attributes.',
                        ],
                    ],
                ],
            ],
            'industry_tips' => [
                [
                    'title' => 'Showcase recent work',
                    'detail' => 'Add a gallery of completed projects to build trust with local customers.',
                ],
            ],
            'google_business_profile' => [
                'likely_present' => false,
                'headline' => 'We could not confirm a Google Business Profile from your site.',
                'steps' => [
                    [
                        'title' => 'Create your profile',
                        'detail' => 'Claim your business at business.google.com and verify your address.',
                    ],
                ],
            ],
        ];
    }
}
