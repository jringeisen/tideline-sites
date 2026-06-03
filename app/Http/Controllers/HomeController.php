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
        $services = [
            [
                'name' => 'Web Design',
                'description' => 'Hand-crafted, lightning-fast websites built to convert visitors into customers.',
                'icon' => 'M3 4.5A1.5 1.5 0 014.5 3h11A1.5 1.5 0 0117 4.5v8a1.5 1.5 0 01-1.5 1.5h-4l.4 2H13a.75.75 0 010 1.5H7a.75.75 0 010-1.5h1.1l.4-2h-4A1.5 1.5 0 013 12.5v-8z',
            ],
            [
                'name' => 'SEO Optimization',
                'description' => 'On-page SEO, local listings, and ongoing optimization so the right people find you on Google.',
                'icon' => 'M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.45 4.39l3.08 3.08a.75.75 0 11-1.06 1.06l-3.08-3.08A7 7 0 012 9z',
            ],
            [
                'name' => 'Blog Writing',
                'description' => 'Original articles that rank, educate your customers, and turn your site into a magnet for search traffic.',
                'icon' => 'M3.5 3A1.5 1.5 0 002 4.5v11A1.5 1.5 0 003.5 17h13a1.5 1.5 0 001.5-1.5v-11A1.5 1.5 0 0016.5 3h-13zM5 7.25A.75.75 0 015.75 6.5h8.5a.75.75 0 010 1.5h-8.5A.75.75 0 015 7.25zM5.75 10a.75.75 0 000 1.5h8.5a.75.75 0 000-1.5h-8.5zM5 13.75a.75.75 0 01.75-.75h5.5a.75.75 0 010 1.5h-5.5a.75.75 0 01-.75-.75z',
            ],
            [
                'name' => 'Newsletters',
                'description' => 'Beautifully designed monthly newsletters that keep your business top-of-mind with past customers.',
                'icon' => 'M2.5 4A1.5 1.5 0 001 5.5v9A1.5 1.5 0 002.5 16h15a1.5 1.5 0 001.5-1.5v-9A1.5 1.5 0 0017.5 4h-15zM3 6.18l6.4 4.27a1.5 1.5 0 001.66 0L17.5 6.18V14.5H3V6.18zM16.1 5.5L10 9.57 3.9 5.5h12.2z',
            ],
        ];

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
                'title' => 'All American Web Design — Custom Websites, Built in America',
                'description' => 'Veteran-owned web design building custom, high-converting websites for American small businesses. Built in America, not outsourced. Plans from $299/month.',
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
