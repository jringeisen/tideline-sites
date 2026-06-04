<?php

namespace App\Http\Controllers;

use App\Support\MarketingSchema;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function __invoke(): Response
    {
        $services = array_values(array_map(
            fn (array $service): array => [
                'slug' => $service['slug'],
                'name' => $service['name'],
                'description' => $service['description'],
                'icon' => $service['icon'],
            ],
            config('offerings'),
        ));

        $faqs = [
            ['question' => 'Do I own my website?', 'answer' => 'Yes, always. The design, content, and domain are yours. If you ever leave us, we hand it off cleanly.'],
            ['question' => 'Is there a contract?', 'answer' => 'No long-term contracts. Essential and Growth are month-to-month, cancel anytime. Build & Own is a one-time build with $20/month hosting you can cancel anytime.'],
            ['question' => 'How fast can my site launch?', 'answer' => 'Most Essential sites launch in one to two weeks from kickoff. Growth plan sites with extra content take two to three weeks.'],
            ['question' => 'Do you work with my industry?', 'answer' => 'We specialize in small service businesses (HVAC, contractors, med spas, lawyers, dentists) and hospitality (restaurants, shops, vacation rentals), for owners across the country.'],
            ['question' => 'What if I already have a website?', 'answer' => 'We can redesign it, optimize what you have, or run SEO and content on your existing site, whichever makes sense.'],
            ['question' => 'Are there setup fees?', 'answer' => 'No setup fees on Essential or Growth. Your first month covers design and launch. Build & Own is a one-time project starting at $1,000; contact us for a quote.'],
        ];

        return Inertia::render('Home', [
            'services' => $services,
            'faqs' => $faqs,
            'features' => [
                'testimonials' => (bool) config('features.testimonials'),
                'businessesLaunched' => (bool) config('features.businesses_launched'),
            ],
            'meta' => [
                'title' => 'Panama City Beach Web Design & SEO — Veteran-Owned, Built in America',
                'description' => 'Veteran-owned web design & SEO for Panama City Beach and Gulf Coast Florida small businesses — from Destin to 30A. Custom sites built in America, not outsourced. Plans from $299/month.',
                'canonical' => url()->current(),
            ],
            'schema' => [
                MarketingSchema::homeBusiness(),
                MarketingSchema::servicesItemList($services),
                MarketingSchema::faqPage($faqs),
                MarketingSchema::website(),
            ],
        ]);
    }
}
