<?php

namespace App\Enums;

enum InquirySource: string
{
    case Contact = 'contact';
    case SeoAssessment = 'seo_assessment';

    /**
     * Human-readable label for display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Contact => 'Contact',
            self::SeoAssessment => 'SEO Assessment',
        };
    }
}
