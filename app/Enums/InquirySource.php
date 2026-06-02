<?php

namespace App\Enums;

enum InquirySource: string
{
    case Contact = 'contact';
    case SeoAssessment = 'seo_assessment';
    case SeoReport = 'seo_report';

    /**
     * Human-readable label for display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Contact => 'Contact',
            self::SeoAssessment => 'SEO Assessment',
            self::SeoReport => 'SEO Report',
        };
    }
}
