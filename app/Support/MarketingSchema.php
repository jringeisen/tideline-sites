<?php

namespace App\Support;

/**
 * Builds JSON-LD structured data arrays for the public marketing pages.
 *
 * These were previously inlined in Blade `@php` blocks; centralising them
 * here keeps the schema output testable and lets controllers pass it to the
 * Inertia pages as a `schema` prop (rendered by MarketingHead.vue).
 *
 * Each method returns an array shaped for `json_encode()` / `JSON.stringify`.
 */
class MarketingSchema
{
    /**
     * The stable @id for the business node, referenced across pages.
     */
    public static function businessId(): string
    {
        return url('/').'#business';
    }

    /**
     * Contact fields for the business node, omitting any that are unset so we
     * never emit a placeholder telephone.
     *
     * @return array<string, string>
     */
    private static function contactFields(): array
    {
        return array_filter([
            'telephone' => config('company.phone'),
            'email' => config('company.email'),
        ]);
    }

    /**
     * Public profile URLs for schema `sameAs` (GBP + socials). Empty when none
     * are configured, in which case callers should omit the key.
     *
     * @return array<int, string>
     */
    private static function sameAs(): array
    {
        return config('company.social', []);
    }

    /**
     * Merge `sameAs` into a schema array only when profiles are configured.
     *
     * @param  array<string, mixed>  $schema
     * @return array<string, mixed>
     */
    private static function withSameAs(array $schema): array
    {
        $sameAs = self::sameAs();

        return $sameAs === [] ? $schema : [...$schema, 'sameAs' => $sameAs];
    }

    /**
     * @return array<string, mixed>
     */
    public static function organization(): array
    {
        return self::withSameAs([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => self::businessId(),
            'name' => 'All American Web Design',
            'url' => url('/'),
            'logo' => asset('og-image.png'),
            'description' => 'A husband-and-wife, veteran-owned web design and SEO studio building custom websites for American small businesses, built in America, not outsourced.',
            'foundingLocation' => ['@type' => 'Place', 'name' => 'Panama City Beach, Florida'],
            'founder' => [
                ['@id' => url()->current().'#jon'],
                ['@id' => url()->current().'#elena'],
            ],
            ...self::contactFields(),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public static function jonPerson(): array
    {
        $pageUrl = url()->current();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            '@id' => $pageUrl.'#jon',
            'name' => 'Jon Ringeisen',
            'jobTitle' => 'Co-founder & Lead Developer',
            'worksFor' => ['@id' => self::businessId()],
            'image' => asset('team/jon-elena.jpeg'),
            'description' => 'Co-founder and lead developer at All American Web Design. 15 years as a software engineer, one SaaS exit, and a US Army veteran.',
            'knowsAbout' => ['Web development', 'SaaS engineering', 'SEO', 'Laravel', 'Vue.js'],
            'alumniOf' => [
                '@type' => 'Organization',
                'name' => 'United States Army',
            ],
            'hasOccupation' => [
                [
                    '@type' => 'Occupation',
                    'name' => 'Software Engineer',
                    'description' => '15 years building software and SaaS products.',
                ],
                [
                    '@type' => 'Occupation',
                    'name' => 'Intelligence Analyst (35F), Sergeant',
                    'description' => 'United States Army. Eight years of service, two combat deployments (Mosul, Iraq and Kuwait).',
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function elenaPerson(): array
    {
        $pageUrl = url()->current();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            '@id' => $pageUrl.'#elena',
            'name' => 'Elena Ringeisen',
            'jobTitle' => 'Co-founder, Marketing & Sales',
            'worksFor' => ['@id' => self::businessId()],
            'image' => asset('team/jon-elena.jpeg'),
            'description' => 'Co-founder of All American Web Design. Entrepreneur, marketer, and a homeschooling mother of four.',
            'knowsAbout' => ['Marketing', 'Sales', 'Customer success', 'Small business growth'],
            'spouse' => ['@id' => $pageUrl.'#jon'],
        ];
    }

    /**
     * ProfessionalService (LocalBusiness) schema for the homepage.
     *
     * @return array<string, mixed>
     */
    public static function homeBusiness(): array
    {
        return self::withSameAs([
            '@context' => 'https://schema.org',
            '@type' => 'ProfessionalService',
            '@id' => self::businessId(),
            'name' => 'All American Web Design',
            'url' => url('/'),
            'description' => 'Veteran-owned web design building custom, high-converting websites for American small businesses — built in America, not outsourced.',
            'priceRange' => '$299 - $499/mo',
            'image' => asset('og-image.png'),
            ...self::contactFields(),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Panama City Beach',
                'addressRegion' => 'FL',
                'addressCountry' => 'US',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => 30.1766,
                'longitude' => -85.8055,
            ],
            'openingHoursSpecification' => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'opens' => '09:00',
                'closes' => '17:00',
            ],
            'areaServed' => [
                '@type' => 'Country',
                'name' => 'United States',
            ],
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => 'All American Web Design Services',
                'itemListElement' => [
                    ['@type' => 'Offer', 'name' => 'Essential', 'price' => '299', 'priceCurrency' => 'USD',
                        'description' => 'Web design and SEO optimization, billed monthly.'],
                    ['@type' => 'Offer', 'name' => 'Growth', 'price' => '499', 'priceCurrency' => 'USD',
                        'description' => 'Web design, SEO optimization, blog writing, and newsletters, billed monthly.'],
                    ['@type' => 'Offer', 'name' => 'Build & Own', 'price' => '1000', 'priceCurrency' => 'USD',
                        'description' => 'One-time custom website build starting at $1,000, plus $20/month for hosting, SSL, backups, and security updates.'],
                ],
            ],
            'founder' => [
                ['@type' => 'Person', 'name' => 'Jon Ringeisen', 'jobTitle' => 'Co-founder & Lead Developer'],
                ['@type' => 'Person', 'name' => 'Elena Ringeisen', 'jobTitle' => 'Co-founder, Marketing & Sales'],
            ],
            'knowsAbout' => ['Web Design', 'SEO', 'Local SEO', 'Laravel', 'Small Business Websites'],
        ]);
    }

    /**
     * ItemList of services for the homepage.
     *
     * @param  array<int, array{name: string, description: string}>  $services
     * @return array<string, mixed>
     */
    public static function servicesItemList(array $services): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'All American Web Design Services',
            'itemListElement' => array_map(fn (int $i, array $service): array => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'item' => [
                    '@type' => 'Service',
                    'name' => $service['name'],
                    'description' => $service['description'],
                    'provider' => ['@id' => self::businessId()],
                    'areaServed' => ['@type' => 'Country', 'name' => 'United States'],
                ],
            ], array_keys($services), $services),
        ];
    }

    /**
     * Service schema for a single service detail page.
     *
     * @param  array{name: string, description: string}  $service
     * @return array<string, mixed>
     */
    public static function service(array $service): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $service['name'],
            'serviceType' => $service['name'],
            'description' => $service['description'],
            'url' => url()->current(),
            'provider' => ['@id' => self::businessId()],
            'areaServed' => [
                ['@type' => 'Country', 'name' => 'United States'],
                ['@type' => 'AdministrativeArea', 'name' => "Florida's Gulf Coast"],
            ],
        ];
    }

    /**
     * FAQPage schema. Reused for the homepage and per-location FAQs.
     *
     * @param  array<int, array{question: string, answer: string}>  $faqs
     * @return array<string, mixed>
     */
    public static function faqPage(array $faqs): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn (array $faq): array => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
            ], $faqs),
        ];
    }

    /**
     * WebSite schema.
     *
     * @return array<string, mixed>
     */
    public static function website(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => url('/').'#website',
            'name' => 'All American Web Design',
            'url' => url('/'),
            'publisher' => ['@id' => self::businessId()],
        ];
    }

    /**
     * ContactPage schema for the contact page.
     *
     * @return array<string, mixed>
     */
    public static function contactPage(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'ContactPage',
            'name' => 'Contact All American Web Design',
            'url' => url()->current(),
            'mainEntity' => [
                '@type' => 'LocalBusiness',
                '@id' => self::businessId(),
                'name' => config('company.name'),
                'areaServed' => 'United States',
                ...self::contactFields(),
            ],
        ];
    }

    /**
     * Service schema for the service-area page, with areaServed built from
     * the nationwide country plus the list of Gulf Coast cities.
     *
     * @param  array<int, string>  $cities
     * @return array<string, mixed>
     */
    public static function serviceArea(array $cities): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => 'Web Design & SEO for American Small Businesses',
            'provider' => ['@id' => self::businessId()],
            'areaServed' => array_merge(
                [['@type' => 'Country', 'name' => 'United States']],
                array_map(fn (string $city): array => [
                    '@type' => 'City',
                    'name' => $city,
                    'containedInPlace' => ['@type' => 'AdministrativeArea', 'name' => "Florida's Gulf Coast"],
                ], $cities),
            ),
        ];
    }

    /**
     * ProfessionalService schema for a single location page.
     *
     * @param  array<string, mixed>  $location
     * @return array<string, mixed>
     */
    public static function localBusinessForLocation(array $location): array
    {
        $pageUrl = url()->current();

        return self::withSameAs([
            '@context' => 'https://schema.org',
            '@type' => 'ProfessionalService',
            '@id' => $pageUrl.'#business',
            'name' => 'All American Web Design — '.$location['name'],
            'url' => $pageUrl,
            'description' => $location['meta_description'],
            'priceRange' => '$299 - $499/mo',
            'image' => asset('og-image.png'),
            ...self::contactFields(),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $location['name'],
                'addressRegion' => 'FL',
                'addressCountry' => 'US',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => $location['geo']['lat'],
                'longitude' => $location['geo']['lng'],
            ],
            'areaServed' => [
                '@type' => 'City',
                'name' => $location['name'],
                'containedInPlace' => ['@type' => 'AdministrativeArea', 'name' => "Florida's Gulf Coast"],
            ],
            'parentOrganization' => ['@id' => self::businessId()],
        ]);
    }

    /**
     * BlogPosting schema for a single post.
     *
     * @param  array{title: string, datePublished: ?string, dateModified: ?string, canonical: string, image: string, authorName: ?string, description: string}  $data
     * @return array<string, mixed>
     */
    public static function blogPosting(array $data): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $data['title'],
            'datePublished' => $data['datePublished'],
            'dateModified' => $data['dateModified'],
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $data['canonical']],
            'image' => $data['image'],
            'author' => [
                '@type' => 'Person',
                'name' => $data['authorName'],
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'All American Web Design',
                'logo' => ['@type' => 'ImageObject', 'url' => asset('og-image.png')],
            ],
            'description' => $data['description'],
        ];
    }

    /**
     * Build a BreadcrumbList from an ordered list of [name => url] pairs.
     *
     * @param  array<int, array{name: string, item: string}>  $items
     * @return array<string, mixed>
     */
    public static function breadcrumb(array $items): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_map(
                fn (array $item, int $index): array => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $item['name'],
                    'item' => $item['item'],
                ],
                $items,
                array_keys($items),
            ),
        ];
    }
}
