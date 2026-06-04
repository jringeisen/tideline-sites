<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Marketing Service Offerings
    |--------------------------------------------------------------------------
    |
    | Single source of truth for the four services offered. The homepage shows
    | the `name`, `description`, and `icon`; the /services index and each
    | /services/{slug} detail page additionally use `tagline`, `intro`,
    | `body`, `includes`, and `faqs`.
    |
    | Named `offerings` because `config/services.php` is reserved by Laravel for
    | third-party service credentials (Mailgun, SES, etc.).
    |
    */

    'web-design' => [
        'slug' => 'web-design',
        'name' => 'Web Design',
        'description' => 'Hand-crafted, lightning-fast websites built to convert visitors into customers.',
        'icon' => 'M3 4.5A1.5 1.5 0 014.5 3h11A1.5 1.5 0 0117 4.5v8a1.5 1.5 0 01-1.5 1.5h-4l.4 2H13a.75.75 0 010 1.5H7a.75.75 0 010-1.5h1.1l.4-2h-4A1.5 1.5 0 013 12.5v-8z',
        'tagline' => 'Custom websites that convert',
        'intro' => 'We design and build custom websites by hand — fast, mobile-first, and built to turn visitors into paying customers. No page builders, no outsourced templates.',
        'body' => [
            'Every site is built from scratch around your business, your customers, and the searches that bring them in. We obsess over load speed, mobile experience, and the handful of actions that actually move the needle: calls, bookings, and quote requests.',
            'You own everything — the design, the content, and the domain. If you ever leave, we hand it off cleanly. For Gulf Coast businesses we pair the build with local SEO so the site ranks where your customers are searching, from Panama City Beach to 30A.',
        ],
        'includes' => [
            'Custom, hand-built design (no templates)',
            'Mobile-first, lightning-fast pages',
            'Conversion-focused layout and calls-to-action',
            'On-page SEO baked in from day one',
            'Hosting, SSL, backups, and security updates',
        ],
        'faqs' => [
            ['question' => 'How much does a custom website cost?', 'answer' => 'Plans start at $299/month for design, build, hosting, and local SEO. Prefer to own it outright? Build & Own is a one-time build from $1,000 plus $20/month for hosting and maintenance.'],
            ['question' => 'How long does a website take to launch?', 'answer' => 'Most Essential sites launch in one to two weeks from kickoff. Sites with extra content or pages take two to three weeks.'],
            ['question' => 'Do I own my website?', 'answer' => 'Yes, always. The design, content, and domain are yours. If you ever leave us, we hand everything off cleanly.'],
        ],
    ],

    'seo' => [
        'slug' => 'seo',
        'name' => 'SEO Optimization',
        'description' => 'On-page SEO, local listings, and ongoing optimization so the right people find you on Google.',
        'icon' => 'M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.45 4.39l3.08 3.08a.75.75 0 11-1.06 1.06l-3.08-3.08A7 7 0 012 9z',
        'tagline' => 'Get found on Google',
        'intro' => 'On-page SEO, Google Business Profile optimization, local citations, and ongoing work so the customers searching for your service actually find you — especially in local results.',
        'body' => [
            'Ranking locally is its own game: the map pack, reviews, citations, and location-relevant content all matter. We handle the technical on-page work, optimize your Google Business Profile, build consistent local listings, and target the searches your customers are really making.',
            'For Gulf Coast businesses we focus on the city and neighborhood terms that convert — "Panama City Beach web design," "Destin charters," "30A vacation rentals" — instead of broad national keywords you will never win and do not need.',
        ],
        'includes' => [
            'On-page and technical SEO',
            'Google Business Profile optimization',
            'Local citations and listings (consistent NAP)',
            'Location-specific landing pages',
            'Ongoing tracking and refinement',
        ],
        'faqs' => [
            ['question' => 'How long does SEO take to work?', 'answer' => 'Local SEO usually shows movement in a few weeks to a few months, depending on how competitive your market and terms are. We focus on the local queries that are within reach first.'],
            ['question' => 'Can you do SEO on my existing website?', 'answer' => 'Yes. We can run SEO and content on a site you already have, or rebuild it if the foundation is holding you back.'],
            ['question' => 'Do you help with Google Business Profile and reviews?', 'answer' => 'We optimize your Google Business Profile and set up a process to earn legitimate reviews — never bought or faked, which can get a profile suspended.'],
        ],
    ],

    'blog-writing' => [
        'slug' => 'blog-writing',
        'name' => 'Blog Writing',
        'description' => 'Original articles that rank, educate your customers, and turn your site into a magnet for search traffic.',
        'icon' => 'M3.5 3A1.5 1.5 0 002 4.5v11A1.5 1.5 0 003.5 17h13a1.5 1.5 0 001.5-1.5v-11A1.5 1.5 0 0016.5 3h-13zM5 7.25A.75.75 0 015.75 6.5h8.5a.75.75 0 010 1.5h-8.5A.75.75 0 015 7.25zM5.75 10a.75.75 0 000 1.5h8.5a.75.75 0 000-1.5h-8.5zM5 13.75a.75.75 0 01.75-.75h5.5a.75.75 0 010 1.5h-5.5a.75.75 0 01-.75-.75z',
        'tagline' => 'Content that pulls in search traffic',
        'intro' => 'Original, well-researched articles that rank for the questions your customers are asking, build trust, and turn your website into a steady source of search traffic.',
        'body' => [
            'Fresh, relevant content tells Google your site is active and authoritative — and it gives your customers a reason to keep coming back. We write articles around the topics and local searches that bring in real business, not filler.',
            'For local businesses, blog content is also a powerful way to reinforce location relevance and link to your service-area pages with natural, localized anchor text.',
        ],
        'includes' => [
            'Keyword and topic research',
            'Original, on-brand articles',
            'On-page SEO for every post',
            'Internal links to your key pages',
            'A consistent publishing cadence',
        ],
        'faqs' => [
            ['question' => 'How often do you publish?', 'answer' => 'Blog writing is part of our $499/month Growth plan, with a regular cadence we agree on up front based on your goals and market.'],
            ['question' => 'Do the articles actually help SEO?', 'answer' => 'Yes — relevant, well-optimized content helps you rank for more searches and gives your site the freshness and depth Google rewards.'],
        ],
    ],

    'newsletters' => [
        'slug' => 'newsletters',
        'name' => 'Newsletters',
        'description' => 'Beautifully designed monthly newsletters that keep your business top-of-mind with past customers.',
        'icon' => 'M2.5 4A1.5 1.5 0 001 5.5v9A1.5 1.5 0 002.5 16h15a1.5 1.5 0 001.5-1.5v-9A1.5 1.5 0 0017.5 4h-15zM3 6.18l6.4 4.27a1.5 1.5 0 001.66 0L17.5 6.18V14.5H3V6.18zM16.1 5.5L10 9.57 3.9 5.5h12.2z',
        'tagline' => 'Stay top-of-mind with customers',
        'intro' => 'Beautifully designed monthly newsletters that keep your business top-of-mind with past customers and turn one-time buyers into repeat business.',
        'body' => [
            'Your past customers are your cheapest source of new business — if you stay in front of them. We design and send monthly newsletters that share updates, offers, and content your audience actually wants to open.',
            'Newsletters pair naturally with blog content: every article becomes a reason to reach back out to your list and drive traffic back to your site.',
        ],
        'includes' => [
            'Custom-designed, on-brand email template',
            'Monthly content written for you',
            'List management and sending',
            'Links back to your site and offers',
        ],
        'faqs' => [
            ['question' => 'Is newsletter service included in a plan?', 'answer' => 'Yes — newsletters are part of our $499/month Growth plan, alongside web design, SEO, and blog writing.'],
            ['question' => 'Do I need an existing email list?', 'answer' => 'It helps, but we can also set up sign-up forms on your site to grow your list over time.'],
        ],
    ],

];
