/**
 * Human-readable label for a contact inquiry source. Mirrors the backend
 * {@link App\Enums\InquirySource::label()}.
 */
export function inquirySourceLabel(source: string): string {
    switch (source) {
        case 'seo_assessment':
            return 'SEO Assessment';
        case 'contact':
            return 'Contact';
        default:
            return source;
    }
}
