/**
 * Human-readable label for a contact inquiry source. Mirrors the backend
 * {@link App\Enums\InquirySource::label()}.
 */
export function inquirySourceLabel(source: string): string {
    switch (source) {
        case 'seo_assessment':
            return 'SEO Assessment';
        case 'seo_report':
            return 'SEO Report';
        case 'contact':
            return 'Contact';
        default:
            return source;
    }
}
