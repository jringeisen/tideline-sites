<?php

use App\Enums\InquirySource;
use App\Enums\PostStatus;

test('post status exposes human-readable labels', function () {
    expect(PostStatus::Draft->label())->toBe('Draft')
        ->and(PostStatus::Scheduled->label())->toBe('Scheduled')
        ->and(PostStatus::Published->label())->toBe('Published');
});

test('post status values returns the backing strings', function () {
    expect(PostStatus::values())->toBe(['draft', 'scheduled', 'published']);
});

test('inquiry source exposes human-readable labels', function () {
    expect(InquirySource::Contact->label())->toBe('Contact')
        ->and(InquirySource::SeoAssessment->label())->toBe('SEO Assessment');
});
