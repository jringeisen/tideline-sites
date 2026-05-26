<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Service;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $deep = $this->deepContent();

        // Pass 1 — upsert every location row.
        foreach ($this->locations() as $sortOrder => $location) {
            Location::query()->updateOrCreate(
                ['slug' => $location['slug']],
                [
                    'name' => $location['name'],
                    'display_name' => $location['display_name'],
                    'region' => $location['region'],
                    'tagline' => $location['tagline'],
                    'hero_subhead' => $location['hero_subhead'],
                    'meta_description' => $location['meta_description'],
                    'intro' => $location['intro'],
                    'why_local' => $location['why_local'],
                    'segments' => $location['segments'],
                    'body' => $deep[$location['slug']]['body'] ?? null,
                    'faqs' => $deep[$location['slug']]['faqs'] ?? null,
                    'lat' => $location['lat'],
                    'lng' => $location['lng'],
                    'sort_order' => $sortOrder,
                    'is_published' => true,
                ],
            );
        }

        // Pass 2 — wire up the "nearby" cross-links and the service offerings.
        $bySlug = Location::query()->get()->keyBy('slug');
        $serviceIds = Service::query()->orderBy('sort_order')->pluck('id')->all();

        foreach ($this->locations() as $location) {
            $model = $bySlug[$location['slug']];

            $nearby = collect($location['nearby'])
                ->filter(fn (string $slug): bool => $bySlug->has($slug))
                ->mapWithKeys(fn (string $slug, int $i): array => [$bySlug[$slug]->id => ['sort_order' => $i]])
                ->all();
            $model->nearby()->sync($nearby);

            $services = collect($serviceIds)
                ->mapWithKeys(fn (int $id, int $i): array => [$id => ['sort_order' => $i]])
                ->all();
            $model->services()->sync($services);
        }
    }

    /**
     * Initial location data, migrated from config/locations.php with HTML
     * entities decoded to plain text (templates escape on output).
     *
     * @return list<array<string, mixed>>
     */
    private function locations(): array
    {
        return [
            [
                'slug' => 'destin',
                'name' => 'Destin',
                'display_name' => 'Destin, FL',
                'region' => 'Emerald Coast',
                'tagline' => 'Web Design & SEO',
                'hero_subhead' => 'From the Destin Harbor to Crab Island, websites that bring local customers to your door.',
                'meta_description' => 'Web design and SEO for Destin, FL businesses. Charter captains, beachfront restaurants, vacation rentals, and local services. Plans from $299/month.',
                'intro' => "Destin's economy runs on local services and a steady stream of visitors looking for charters, restaurants, and rentals. We build sites that rank for the searches your future customers are actually making in the Destin area, and we run the SEO that keeps you there.",
                'why_local' => "We live and work on Florida's Gulf Coast. We know what 'shoulder season' means for a Destin charter operator's cash flow, and we know the difference between a tourist searching from out of state and a local searching from East Pass. Your site is built for both.",
                'segments' => [
                    ['title' => 'Charter & fishing operators', 'body' => 'Get found by anglers searching for half-day charters, deep-sea trips, and family-friendly boats well before they land in Destin.'],
                    ['title' => 'Beach restaurants & bars', 'body' => 'Beat the chains in local search results with a fast site that converts hungry visitors into reservations.'],
                    ['title' => 'Vacation rentals & real estate', 'body' => 'Show off your properties with a beautiful site optimized for "Destin vacation rentals" and similar local terms.'],
                    ['title' => 'Local service businesses', 'body' => 'Plumbers, electricians, contractors, med spas, and dentists. We help year-round businesses win year-round customers.'],
                ],
                'nearby' => ['30a', 'panama-city-beach', 'miramar-beach', 'sandestin', 'fort-walton-beach'],
                'lat' => 30.3935,
                'lng' => -86.4958,
            ],
            [
                'slug' => 'panama-city-beach',
                'name' => 'Panama City Beach',
                'display_name' => 'Panama City Beach, FL',
                'region' => 'Bay County',
                'tagline' => 'Web Design & SEO',
                'hero_subhead' => 'From Pier Park to Front Beach Road, websites that work as hard as PCB does.',
                'meta_description' => 'Web design and SEO for Panama City Beach businesses. Vacation rentals, restaurants, charter operators, med spas, and local services. Plans from $299/month.',
                'intro' => 'Panama City Beach is one of the busiest tourist markets on the Gulf, and the local search competition is fierce. We build websites and run SEO programs that help PCB businesses win year-round, not just during spring break.',
                'why_local' => "We're based in Panama City Beach. We know the difference between a Front Beach Road condo manager and a Thomas Drive seafood spot, and your site is built around the customer you actually serve.",
                'segments' => [
                    ['title' => 'Vacation rental managers', 'body' => 'Custom property sites and listing pages that rank for "PCB vacation rentals" and the specific neighborhoods you manage.'],
                    ['title' => 'Restaurants & family attractions', 'body' => 'Mobile-fast menus, reservations, and Google profile optimization so visitors find you the moment they arrive.'],
                    ['title' => 'Charter, dive & tour operators', 'body' => 'Booking-friendly sites that capture customers researching trips weeks before their vacation.'],
                    ['title' => 'Med spas & local services', 'body' => 'Year-round local SEO that brings in PCB and Lynn Haven residents, not just tourists.'],
                ],
                'nearby' => ['30a', 'destin', 'panama-city', 'lynn-haven', 'mexico-beach'],
                'lat' => 30.1766,
                'lng' => -85.8055,
            ],
            [
                'slug' => '30a',
                'name' => '30A',
                'display_name' => '30A, Florida',
                'region' => '30A',
                'tagline' => 'Web Design & SEO',
                'hero_subhead' => 'From Grayton to Rosemary Beach, websites built for the 30A way.',
                'meta_description' => 'Web design and SEO for 30A businesses. Boutiques, vacation rentals, restaurants, and service pros from Seaside to Alys Beach. Plans from $299/month.',
                'intro' => '30A businesses live by their brand. Seaside, WaterColor, Alys, Rosemary. Each town has a distinct look and a customer with high expectations. We build sites that hold up next to your storefront, then run the SEO that brings the right people through the door.',
                'why_local' => 'We work with 30A businesses all the way from Dune Allen to Inlet Beach. We understand the difference between a Watercolor real estate office and a Seacrest boutique. Your site is designed to look at home in your specific town.',
                'segments' => [
                    ['title' => 'Boutiques & galleries', 'body' => 'Beautiful, on-brand websites that match the design standard of your storefront and rank for "30A shopping" and town-specific searches.'],
                    ['title' => 'Vacation rental managers', 'body' => 'Property sites with the polish 30A guests expect, optimized for the towns and neighborhoods you actually manage.'],
                    ['title' => 'Restaurants & cafés', 'body' => 'Menus, reservations, and content that win the locals-and-visitors mix that 30A restaurants depend on.'],
                    ['title' => 'Real estate & design pros', 'body' => "Agent and studio sites built to compete in one of Florida's most competitive markets."],
                ],
                'nearby' => ['destin', 'panama-city-beach', 'seaside', 'watercolor', 'rosemary-beach', 'alys-beach'],
                'lat' => 30.3299,
                'lng' => -86.1559,
            ],
            [
                'slug' => 'miramar-beach',
                'name' => 'Miramar Beach',
                'display_name' => 'Miramar Beach, FL',
                'region' => 'Emerald Coast',
                'tagline' => 'Web Design & SEO',
                'hero_subhead' => 'From Scenic 98 to Silver Sands, websites that turn vacation traffic into year-round customers.',
                'meta_description' => 'Web design and SEO for Miramar Beach, FL businesses. Resort shops, restaurants, vacation rentals, and local services along Scenic 98. Plans from $299/month.',
                'intro' => 'Miramar Beach sits in the sweet spot between Destin and Sandestin, with a steady flow of resort guests, second-home owners, and locals running real businesses. We build sites that capture the searches happening from Scenic 98 condos and the outlets at Silver Sands, and run the SEO that keeps you ranking after the season ends.',
                'why_local' => 'We know Miramar Beach isn\'t just "east of Destin". It has its own crowd, its own price points, and its own search patterns. Whether you\'re on Scenic Gulf Drive or tucked off 98, your site is built around the customers actually looking for you.',
                'segments' => [
                    ['title' => 'Resort shops & boutiques', 'body' => 'Storefront-quality websites that hold up next to Silver Sands and pull in shoppers searching before they arrive.'],
                    ['title' => 'Restaurants & beach bars', 'body' => 'Mobile-fast menus and Google profiles tuned for guests deciding where to eat from a Miramar Beach balcony.'],
                    ['title' => 'Vacation rental managers', 'body' => 'Property pages that rank for "Miramar Beach vacation rentals" and the specific complexes you manage along Scenic Gulf Drive.'],
                    ['title' => 'Local service businesses', 'body' => 'HVAC, cleaning, contractors, and med spas. Year-round SEO that brings in residents, not just tourists.'],
                ],
                'nearby' => ['destin', 'sandestin', '30a', 'fort-walton-beach', 'panama-city-beach'],
                'lat' => 30.3838,
                'lng' => -86.3618,
            ],
            [
                'slug' => 'sandestin',
                'name' => 'Sandestin',
                'display_name' => 'Sandestin, FL',
                'region' => 'Emerald Coast',
                'tagline' => 'Web Design & SEO',
                'hero_subhead' => 'From Baytowne Wharf to the Burnt Pine fairways, websites built for the Sandestin standard.',
                'meta_description' => 'Web design and SEO for Sandestin businesses. Resort shops, dining, golf, marina services, and vacation rentals inside the gates and along 98. Plans from $299/month.',
                'intro' => "Sandestin guests expect polish. Whether they're booking a tee time at Burnt Pine, a dinner at Baytowne Wharf, or a week at a bayfront rental, they're researching from their phone the second they decide to come down. We build sites that match the resort's design standard and run the SEO that wins those searches.",
                'why_local' => 'We\'ve spent enough time inside the gates to know how different a Baytowne shop is from a Linkside condo manager or a marina charter operator. Your site is built for your specific corner of the resort, not a generic "Destin-area" template.',
                'segments' => [
                    ['title' => 'Resort shops & dining', 'body' => 'On-brand sites and search visibility for businesses at Baytowne Wharf and around the resort.'],
                    ['title' => 'Vacation rentals & condo managers', 'body' => "Listings and search optimization for Sandestin's bayside, beachside, and golf-course properties."],
                    ['title' => 'Golf, tennis & marina services', 'body' => 'Booking-friendly sites that capture guests planning Sandestin trips weeks in advance.'],
                    ['title' => 'Wedding & event vendors', 'body' => 'Photographer, florist, and planner sites tuned for couples searching "Sandestin weddings".'],
                ],
                'nearby' => ['miramar-beach', 'destin', '30a', 'fort-walton-beach', 'panama-city-beach'],
                'lat' => 30.3838,
                'lng' => -86.3293,
            ],
            [
                'slug' => 'fort-walton-beach',
                'name' => 'Fort Walton Beach',
                'display_name' => 'Fort Walton Beach, FL',
                'region' => 'Emerald Coast',
                'tagline' => 'Web Design & SEO',
                'hero_subhead' => "From Okaloosa Island to the Eglin gate, websites built for FWB's year-round economy.",
                'meta_description' => 'Web design and SEO for Fort Walton Beach businesses. Local services, restaurants, military-friendly shops, and Okaloosa Island rentals. Plans from $299/month.',
                'intro' => "Fort Walton Beach runs differently than Destin. There's a real year-round base of military families, retirees, and locals, plus a strong tourist pull on Okaloosa Island. We build sites and SEO programs that win the everyday searches, not just the spring-and-summer rush.",
                'why_local' => 'We understand the FWB customer mix. Eglin and Hurlburt households, longtime residents on Beal Parkway, and visitors crossing the bridge to the island. Your site is built to bring in the customer base you actually serve.',
                'segments' => [
                    ['title' => 'Local service businesses', 'body' => 'Plumbers, HVAC, auto repair, contractors. Pages that rank for FWB neighborhood searches all year.'],
                    ['title' => 'Restaurants & family attractions', 'body' => 'Mobile-fast menus and Google profiles that win both locals and Okaloosa Island visitors.'],
                    ['title' => 'Military-friendly retailers & services', 'body' => 'Sites that speak to Eglin and Hurlburt families, with content that ranks for "near Eglin" searches.'],
                    ['title' => 'Okaloosa Island rentals & charters', 'body' => "Booking-ready property and trip sites optimized for the island's specific search terms."],
                ],
                'nearby' => ['destin', 'miramar-beach', 'sandestin', '30a'],
                'lat' => 30.4058,
                'lng' => -86.6187,
            ],
            [
                'slug' => 'panama-city',
                'name' => 'Panama City',
                'display_name' => 'Panama City, FL',
                'region' => 'Bay County',
                'tagline' => 'Web Design & SEO',
                'hero_subhead' => 'From Historic St. Andrews to Harrison Avenue, websites built for the real, year-round Panama City.',
                'meta_description' => 'Web design and SEO for Panama City, FL businesses. Downtown shops, St. Andrews restaurants, contractors, medical practices, and local services. Plans from $299/month.',
                'intro' => "Panama City is where the year-round economy lives. Downtown's coming back, St. Andrews is full of local favorites, and the surrounding neighborhoods are packed with the trades, medical practices, and service businesses that keep the area running. We build sites and SEO programs designed for that everyday customer, not just the beach crowd.",
                'why_local' => 'We know the difference between a Harrison Avenue retailer, a Cove dentist, and a contractor working out of Lynn Haven. Your site is built for the customers in your actual service area, not lumped in with Panama City Beach.',
                'segments' => [
                    ['title' => 'Trades & home services', 'body' => 'Roofers, electricians, plumbers, and remodelers. Local SEO that wins neighborhood-level searches across Bay County.'],
                    ['title' => 'Medical, dental & professional', 'body' => 'Practice sites and content that rank for Panama City service searches, not generic state-wide terms.'],
                    ['title' => 'Downtown & St. Andrews shops', 'body' => "On-brand sites for the small businesses driving Panama City's comeback."],
                    ['title' => 'Restaurants & cafés', 'body' => 'Mobile-fast menus and Google profile work that brings in locals night after night.'],
                ],
                'nearby' => ['panama-city-beach', 'lynn-haven', 'mexico-beach', '30a'],
                'lat' => 30.1588,
                'lng' => -85.6602,
            ],
            [
                'slug' => 'lynn-haven',
                'name' => 'Lynn Haven',
                'display_name' => 'Lynn Haven, FL',
                'region' => 'Bay County',
                'tagline' => 'Web Design & SEO',
                'hero_subhead' => "From Sheffield Park to Highway 77, websites built for Lynn Haven's growing year-round community.",
                'meta_description' => 'Web design and SEO for Lynn Haven, FL businesses. Contractors, medical practices, family-owned shops, and local services. Plans from $299/month.',
                'intro' => 'Lynn Haven keeps growing. New families, new builds, and a steady demand for local services. We build websites and run SEO that puts your business in front of the homeowners actually moving in and searching for you on Google.',
                'why_local' => "We work with businesses across Bay County and we know Lynn Haven's customer is different from Panama City Beach's. Your site is tuned for the year-round residents, families, and local pros who shop here every day.",
                'segments' => [
                    ['title' => 'Trades & home services', 'body' => 'Contractors, lawn care, HVAC, and remodelers. Content that ranks for "Lynn Haven" plus the services you actually offer.'],
                    ['title' => 'Medical & dental practices', 'body' => 'Patient-friendly sites tuned for the families and retirees who choose providers close to home.'],
                    ['title' => 'Family-owned shops & restaurants', 'body' => 'On-brand sites and Google profile work that win local search across Lynn Haven.'],
                    ['title' => 'Professional services', 'body' => 'Attorneys, accountants, and insurance offices. Local SEO that brings in Lynn Haven and north Bay County clients.'],
                ],
                'nearby' => ['panama-city', 'panama-city-beach', 'mexico-beach', '30a'],
                'lat' => 30.2466,
                'lng' => -85.6479,
            ],
            [
                'slug' => 'mexico-beach',
                'name' => 'Mexico Beach',
                'display_name' => 'Mexico Beach, FL',
                'region' => 'Bay County',
                'tagline' => 'Web Design & SEO',
                'hero_subhead' => "From the canal to Highway 98, websites built for Mexico Beach's small-town comeback.",
                'meta_description' => 'Web design and SEO for Mexico Beach, FL businesses. Vacation rentals, restaurants, charter captains, and small-town service pros. Plans from $299/month.',
                'intro' => 'Mexico Beach is rebuilding into something special, quieter than PCB, with a loyal returning crowd and a tight-knit business community. We build sites and run SEO that puts you in front of the visitors planning their trip and the locals choosing where to spend.',
                'why_local' => "We know Mexico Beach isn't trying to be Panama City Beach, and we won't market it that way. Your site reflects the slower, smaller, repeat-visitor town your customers are actually coming to find.",
                'segments' => [
                    ['title' => 'Vacation rental managers & owners', 'body' => 'Property sites that capture the families who book Mexico Beach year after year.'],
                    ['title' => 'Restaurants & small shops', 'body' => 'Sites and Google profiles that win the visitor researching dinner from a canal-side rental.'],
                    ['title' => 'Charter captains & outdoor outfitters', 'body' => 'Booking-friendly trip sites optimized for anglers searching well before they arrive.'],
                    ['title' => 'Local service businesses', 'body' => 'Contractors, cleaners, and trades. Year-round SEO for the residents and second-home owners rebuilding the community.'],
                ],
                'nearby' => ['panama-city-beach', 'panama-city', 'lynn-haven', '30a'],
                'lat' => 29.9469,
                'lng' => -85.4116,
            ],
            [
                'slug' => 'seaside',
                'name' => 'Seaside',
                'display_name' => 'Seaside, FL',
                'region' => '30A',
                'tagline' => 'Web Design & SEO',
                'hero_subhead' => 'From the Amphitheater to Central Square, websites that match the Seaside standard.',
                'meta_description' => 'Web design and SEO for Seaside, FL businesses. Boutiques, restaurants, galleries, and vacation rentals at the original 30A town. Plans from $299/month.',
                'intro' => 'Seaside set the design standard the rest of 30A is still chasing. Your customers — boutique shoppers, second-home owners, design-conscious families — expect a website that lives up to the storefront. We build sites that do, and run the SEO that brings the right kind of traffic to your door.',
                'why_local' => 'Working with Seaside businesses means respecting the brand of the town itself. Pastel-perfect, walkable, hand-built. Your site should feel like an extension of Central Square, not a generic real-estate-template knockoff.',
                'segments' => [
                    ['title' => 'Boutiques, galleries & design shops', 'body' => 'On-brand sites that match the Seaside aesthetic and rank for shopping searches up and down 30A.'],
                    ['title' => 'Restaurants & cafés', 'body' => 'Menus, reservations, and Google profile work tuned for the locals-and-visitors mix Seaside spots depend on.'],
                    ['title' => 'Vacation rentals & cottage managers', 'body' => 'Property pages with the polish Seaside guests expect, plus SEO for the specific cottages you manage.'],
                    ['title' => 'Real estate & design pros', 'body' => "Agent and studio sites built to compete in one of Florida's most premium markets."],
                ],
                'nearby' => ['watercolor', '30a', 'rosemary-beach', 'alys-beach', 'destin'],
                'lat' => 30.3211,
                'lng' => -86.1481,
            ],
            [
                'slug' => 'watercolor',
                'name' => 'WaterColor',
                'display_name' => 'WaterColor, FL',
                'region' => '30A',
                'tagline' => 'Web Design & SEO',
                'hero_subhead' => 'From Western Lake to the BeachClub, websites built for the WaterColor brand.',
                'meta_description' => "Web design and SEO for WaterColor, FL businesses. Vacation rentals, restaurants, boutiques, and design pros at one of 30A's premier communities. Plans from $299/month.",
                'intro' => "WaterColor guests come for the dune lake, the BeachClub, and the kind of design-led vacation you can't fake. We build sites and SEO programs for the businesses serving that customer, without the generic \"30A vacation rental\" template the search results are full of.",
                'why_local' => 'We know the WaterColor customer is different from a Seacrest renter or a Destin condo guest. Your site is built around the price point, the polish, and the searches that actually convert here.',
                'segments' => [
                    ['title' => 'Vacation rental managers', 'body' => 'Property sites and listing pages optimized for WaterColor cottages, homes, and BeachClub-access rentals.'],
                    ['title' => 'Boutiques & lifestyle shops', 'body' => 'On-brand websites that match the WaterColor design standard and pull in shoppers from across 30A.'],
                    ['title' => 'Restaurants & cafés', 'body' => 'Menus and Google profiles tuned for the guests deciding where to eat from a Western Lake porch.'],
                    ['title' => 'Real estate & design pros', 'body' => "Agent and studio sites built for one of 30A's most competitive markets."],
                ],
                'nearby' => ['seaside', '30a', 'rosemary-beach', 'alys-beach', 'destin'],
                'lat' => 30.3303,
                'lng' => -86.1361,
            ],
            [
                'slug' => 'rosemary-beach',
                'name' => 'Rosemary Beach',
                'display_name' => 'Rosemary Beach, FL',
                'region' => '30A',
                'tagline' => 'Web Design & SEO',
                'hero_subhead' => "From Town Hall to the boardwalks, websites built for Rosemary's distinctive standard.",
                'meta_description' => "Web design and SEO for Rosemary Beach, FL businesses. Vacation rentals, boutiques, restaurants, and design pros at one of 30A's most distinctive towns. Plans from $299/month.",
                'intro' => 'Rosemary Beach has a look you can spot from a block away. Dutch West Indies architecture, narrow walkways, deep porches, and a customer who notices the difference. We build websites that hold up to that level of detail, and run the SEO that brings the right guests through your door.',
                'why_local' => "We've worked with enough 30A businesses to know Rosemary's not just another beach town. Your site is built around the design sensibility, price point, and customer Rosemary actually attracts.",
                'segments' => [
                    ['title' => 'Boutiques & galleries', 'body' => "On-brand sites that match Rosemary's storefront standard and rank for east-end-of-30A shopping searches."],
                    ['title' => 'Restaurants & wine bars', 'body' => 'Menus, reservations, and Google profile work for the locals-and-visitors mix Rosemary spots count on.'],
                    ['title' => 'Vacation rental managers', 'body' => 'Property sites with the polish Rosemary guests expect, optimized for the specific homes you manage.'],
                    ['title' => 'Real estate & design pros', 'body' => "Agent and studio sites built to compete in one of 30A's most premium pockets."],
                ],
                'nearby' => ['alys-beach', 'seaside', 'watercolor', '30a', 'panama-city-beach'],
                'lat' => 30.2773,
                'lng' => -85.9444,
            ],
            [
                'slug' => 'alys-beach',
                'name' => 'Alys Beach',
                'display_name' => 'Alys Beach, FL',
                'region' => '30A',
                'tagline' => 'Web Design & SEO',
                'hero_subhead' => 'From the white walls to the Caliza pool, websites built for the Alys standard.',
                'meta_description' => "Web design and SEO for Alys Beach, FL businesses. Vacation rentals, restaurants, design pros, and boutiques at 30A's most architectural town. Plans from $299/month.",
                'intro' => 'Alys Beach is unlike anything else on 30A. All-white architecture, courtyards, and a level of design discipline you only get when an entire town is built to one standard. We build websites and run SEO for businesses that have to live up to that, without looking like every other beach-town site on the search results page.',
                'why_local' => 'We respect the Alys brand. Your site should look like it belongs across the courtyard from Caliza. Restrained, considered, and built for guests who notice every detail.',
                'segments' => [
                    ['title' => 'Vacation rentals & private homes', 'body' => 'Property pages with the restraint and polish Alys guests expect.'],
                    ['title' => 'Restaurants & private dining', 'body' => 'Menus, reservations, and Google profile work for Alys spots competing on experience, not volume.'],
                    ['title' => 'Boutiques & design retail', 'body' => 'On-brand sites that hold up next to the architecture and rank for the searches your guests are actually running.'],
                    ['title' => 'Architects, designers & real estate', 'body' => "Studio and agent sites built to compete in one of the country's most design-aware markets."],
                ],
                'nearby' => ['rosemary-beach', 'seaside', 'watercolor', '30a', 'panama-city-beach'],
                'lat' => 30.2746,
                'lng' => -85.9805,
            ],
        ];
    }

    /**
     * Genuinely local, in-depth body copy and FAQs keyed by slug. This is the
     * content that lifts each page out of "thin geo-template" territory.
     * Draft copy — review for factual accuracy before publishing.
     *
     * @return array<string, array{body: string, faqs: list<array{question: string, answer: string}>}>
     */
    private function deepContent(): array
    {
        return [
            'destin' => [
                'body' => <<<'HTML'
<h2>What it takes to rank in Destin</h2>
<p>Destin search is two markets in one. Half your customers are tourists planning a trip from Tennessee or Georgia, typing "Destin fishing charter" or "best seafood in Destin" weeks before they arrive. The other half are locals from Crystal Beach, Holiday Isle, and Kelly Plantation looking for a plumber, a dentist, or a place for dinner tonight. A site that only speaks to one of them leaves money on the table, so we build pages and content that capture both intents.</p>
<p>The competition here is fierce because the dollars are real. Charter captains off the Destin Harbor, restaurants along U.S. 98, and rental managers on Holiday Isle are all bidding for the same clicks. We win those rankings the durable way: fast, mobile-first pages, a properly optimized Google Business Profile, genuine local content, and the technical SEO most template sites skip.</p>
<h2>Built for the Destin season — and the shoulder season</h2>
<p>March through August is when the harbor is packed and the searches spike. We make sure your site is ready for that traffic, then keep your rankings warm through the fall and winter so you are still the first name a local sees in October.</p>
HTML,
                'faqs' => [
                    ['question' => 'Do you only work with tourism businesses in Destin?', 'answer' => 'No. We work with charter captains and restaurants, but also year-round Destin service businesses — contractors, med spas, dentists, and home services — that depend on local residents, not just visitors.'],
                    ['question' => 'How long until my Destin business ranks on Google?', 'answer' => 'Most clients see early movement on local and long-tail searches within a few weeks, with stronger competitive rankings building over three to six months of consistent on-page work and content.'],
                    ['question' => 'Can you optimize my Google Business Profile for Destin searches?', 'answer' => 'Yes. Local map-pack visibility is one of the biggest levers for a Destin business, so Google Business Profile optimization is part of how we approach local SEO here.'],
                ],
            ],
            'panama-city-beach' => [
                'body' => <<<'HTML'
<h2>Winning year-round in Panama City Beach</h2>
<p>PCB runs hot. Front Beach Road, Pier Park, and Thomas Drive draw an enormous volume of seasonal traffic, and the local search competition reflects it. Vacation rental managers, restaurants, and tour operators are all fighting for the same high-intent searches during spring break and summer. We build sites engineered to convert that rush — fast menus, frictionless booking, and Google Business Profiles that surface you the moment a visitor's phone hits the sand.</p>
<p>But the businesses that thrive in PCB are the ones that do not disappear in the off-season. Lynn Haven and PCB residents need med spas, contractors, and local services all year. We pair the seasonal play with year-round local SEO so your phone keeps ringing in November, not just July.</p>
<h2>Neighborhood-level targeting</h2>
<p>"PCB vacation rentals" is one search; "Front Beach Road condo near Pier Park" is another, and it converts far better. We build the neighborhood and landmark-level pages that capture those specific, ready-to-book searches instead of competing only on the broad, expensive terms.</p>
HTML,
                'faqs' => [
                    ['question' => 'Can you help my PCB rental business compete with the big listing sites?', 'answer' => 'Yes. We build direct-booking property sites and neighborhood-specific landing pages that rank for the searches the big aggregators water down, so more guests book with you directly.'],
                    ['question' => 'Is local SEO worth it if my PCB business is seasonal?', 'answer' => 'It is — partly to dominate the season, and partly to keep your rankings warm so you capture the year-round Panama City Beach and Lynn Haven residents most seasonal competitors ignore.'],
                    ['question' => 'Do you work with restaurants and attractions near Pier Park?', 'answer' => 'Yes. Mobile-fast menus, reservation links, and Google Business Profile optimization are exactly how we help PCB restaurants and family attractions get found by visitors deciding where to go next.'],
                ],
            ],
            '30a' => [
                'body' => <<<'HTML'
<h2>A website that lives up to 30A</h2>
<p>30A is a brand before it is a road. From Grayton Beach to Inlet Beach, each town — Seaside, WaterColor, Rosemary, Alys — has a distinct look and a customer who notices the difference between considered design and a generic template. A boutique in Seacrest cannot afford to look like every other "beach vacation rental" site on the results page, and neither can you. We build sites that hold up next to your storefront.</p>
<p>The 30A customer also researches hard before they buy. They are comparing rentals, restaurants, and shops from a phone weeks ahead of their trip, with high expectations and real budgets. We make sure your site loads instantly, looks the part, and ranks for the town-specific searches that actually convert here.</p>
<h2>Town-by-town, not one-size-fits-all</h2>
<p>"30A shopping" is broad and competitive. "Rosemary Beach boutique" or "Alys Beach dinner reservations" are specific and lucrative. We build the town-level content and structure that wins those tighter searches across the whole corridor.</p>
HTML,
                'faqs' => [
                    ['question' => 'My 30A business is design-conscious. Can your sites match that standard?', 'answer' => 'That is the whole point. We design sites that look at home next to a Seaside or Alys Beach storefront — not a stock template — while still ranking and converting.'],
                    ['question' => 'Should I target all of 30A or my specific town?', 'answer' => 'Both. We build broad 30A visibility plus town-specific pages for Seaside, WaterColor, Rosemary, Alys, and the others, since those tighter searches convert far better.'],
                    ['question' => 'Do you work with vacation rental managers along 30A?', 'answer' => 'Yes. We build polished property sites optimized for the specific towns and neighborhoods you manage, so guests booking a 30A trip find your homes first.'],
                ],
            ],
            'miramar-beach' => [
                'body' => <<<'HTML'
<h2>The sweet spot between Destin and Sandestin</h2>
<p>Miramar Beach is not just "east of Destin," and marketing it that way leaves customers on the table. Scenic Gulf Drive condos, the outlets at Silver Sands, and the resort crowd give it its own search patterns and price points. We build sites that capture the guest deciding where to shop or eat from a Miramar Beach balcony, then keep ranking after the season winds down.</p>
<p>It is also a real year-round community. HVAC companies, cleaners, contractors, and med spas here serve residents and second-home owners who search Google like everyone else. We make sure your business shows up for both the vacation traffic and the local, repeat customer.</p>
<h2>Scenic 98 and Silver Sands intent</h2>
<p>Shoppers searching near Silver Sands and guests along Scenic Gulf Drive are high-intent and close to buying. We build the location-aware content and Google Business Profile presence that turn those nearby searches into walk-ins and bookings.</p>
HTML,
                'faqs' => [
                    ['question' => 'Is Miramar Beach too small a market for SEO to matter?', 'answer' => 'Not at all. The Scenic 98 and Silver Sands corridor has steady, high-intent search volume, and being the business that ranks for it — instead of getting lumped under "Destin" — is a real advantage.'],
                    ['question' => 'Can you help a year-round service business in Miramar Beach?', 'answer' => 'Yes. We build local SEO that targets residents and second-home owners, not just tourists, so HVAC, cleaning, contracting, and med spa businesses get found all year.'],
                    ['question' => 'Do you build property sites for Scenic Gulf Drive rentals?', 'answer' => 'Yes. We build rental sites optimized for the specific complexes you manage along Scenic Gulf Drive, ranking for the searches guests actually use.'],
                ],
            ],
            'sandestin' => [
                'body' => <<<'HTML'
<h2>Polish that matches the resort</h2>
<p>Sandestin guests expect a certain standard, whether they are booking a tee time at Burnt Pine, a dinner at Baytowne Wharf, or a week in a bayfront rental. Your website is often their first impression, and it has to look the part the second it loads on a phone. We build sites tuned to the resort's design standard and the customer who chooses Sandestin specifically.</p>
<p>Inside the gates, the businesses are remarkably different from one another — a Baytowne shop, a Linkside condo manager, and a marina charter operator all serve distinct customers. We build for your specific corner of the resort rather than a generic "Destin-area" approach, and we run the SEO that captures guests planning their trip weeks ahead.</p>
<h2>Booking-intent searches, weeks in advance</h2>
<p>The most valuable Sandestin searches happen before the guest ever arrives. We build the content and booking paths that capture trip-planning intent early, so you are the option they remember when it is time to reserve.</p>
HTML,
                'faqs' => [
                    ['question' => 'Do you work with businesses inside the Sandestin resort?', 'answer' => 'Yes — Baytowne Wharf shops and dining, condo and rental managers, and golf, tennis, and marina services. We tailor each site to that specific corner of the resort.'],
                    ['question' => 'Can you help wedding and event vendors in Sandestin?', 'answer' => 'Yes. Photographers, florists, and planners get sites and content tuned for couples searching "Sandestin weddings" and planning months in advance.'],
                    ['question' => 'My rental competes with the resort\'s own booking. Can a site help?', 'answer' => 'It can. A polished, well-optimized direct-booking site captures guests who would otherwise book elsewhere and keeps more of the reservation in your pocket.'],
                ],
            ],
            'fort-walton-beach' => [
                'body' => <<<'HTML'
<h2>A year-round economy, not just a beach town</h2>
<p>Fort Walton Beach plays differently than Destin. There is a deep year-round base of military families tied to Eglin and Hurlburt, retirees, and longtime residents along Beal Parkway, plus a strong tourist pull on Okaloosa Island. The businesses that win here are built to capture everyday searches all year, not just the spring-and-summer rush. We build sites and SEO programs around that steadier, local-first demand.</p>
<p>That military community is a real market with its own search behavior. Retailers and service businesses that speak to Eglin and Hurlburt households — and rank for "near Eglin" searches — earn loyal, repeat customers. We make sure your site connects with the FWB customer base you actually serve.</p>
<h2>Island traffic and mainland locals</h2>
<p>Okaloosa Island rentals and charters need booking-ready sites tuned to the island's search terms, while mainland trades and restaurants need year-round local visibility. We build for both sides of the bridge.</p>
HTML,
                'faqs' => [
                    ['question' => 'Is Fort Walton Beach a good market for year-round SEO?', 'answer' => 'Yes — arguably better than the pure-tourism towns. The military, retiree, and resident base means consistent year-round search demand for local services, not just a summer spike.'],
                    ['question' => 'Can you help businesses that serve Eglin and Hurlburt families?', 'answer' => 'Yes. We build content and local SEO that speaks to the military community and ranks for the "near Eglin" and FWB neighborhood searches those households use.'],
                    ['question' => 'Do you work with Okaloosa Island rentals and charters?', 'answer' => 'Yes. We build booking-ready property and trip sites optimized for the island\'s specific search terms, separate from the broader Destin market.'],
                ],
            ],
            'panama-city' => [
                'body' => <<<'HTML'
<h2>The real, year-round Panama City</h2>
<p>Panama City is where Bay County's everyday economy lives. Downtown is coming back, Historic St. Andrews is full of local favorites, and the surrounding neighborhoods are packed with the trades, medical practices, and professional services that keep the area running. These businesses serve residents, not the beach crowd, so we build sites and SEO designed for the customer searching from the Cove, the Heights, or Lynn Haven — not a tourist.</p>
<p>Getting lumped in with Panama City Beach is a common and costly mistake. A Harrison Avenue retailer, a Cove dentist, and a contractor working out of town all serve very different people. We build for your actual service area and the neighborhood-level searches that bring in local customers.</p>
<h2>Trades, medical, and downtown retail</h2>
<p>Roofers, electricians, and remodelers win on neighborhood-level local SEO across Bay County. Practices and professional offices win on trust and specific service searches. Downtown and St. Andrews shops win on brand and Google visibility. We build the right approach for each.</p>
HTML,
                'faqs' => [
                    ['question' => 'Will you market my Panama City business like a beach business?', 'answer' => 'No. Panama City is a year-round, resident-driven market. We build for the local customer in your actual service area, not the Panama City Beach tourist crowd.'],
                    ['question' => 'Do you work with trades and home-service businesses?', 'answer' => 'Yes. Roofers, electricians, plumbers, and remodelers are a core focus — we build local SEO that wins neighborhood-level searches across Bay County.'],
                    ['question' => 'Can you help a downtown or St. Andrews small business?', 'answer' => 'Yes. We build on-brand sites and Google Business Profile visibility for the shops and restaurants driving the Panama City and St. Andrews comeback.'],
                ],
            ],
            'lynn-haven' => [
                'body' => <<<'HTML'
<h2>Built for a growing community</h2>
<p>Lynn Haven keeps growing — new families, new construction, and steady demand for local services. The homeowners moving in around Sheffield Park and along Highway 77 are searching Google for contractors, dentists, lawn care, and the everyday businesses a growing community needs. We build sites and SEO that put your business in front of them at exactly that moment.</p>
<p>The Lynn Haven customer is different from the Panama City Beach customer, and treating them the same is a mistake. This is a residential, family-driven market where trust and proximity matter. We tune your site and content for the year-round residents and local pros who shop here every day.</p>
<h2>North Bay County reach</h2>
<p>Lynn Haven businesses often serve north Bay County and Southport too. We build content that ranks for "Lynn Haven" plus the specific services you offer, and extends your reach into the surrounding growth.</p>
HTML,
                'faqs' => [
                    ['question' => 'Is Lynn Haven big enough to invest in SEO?', 'answer' => 'Yes. It is one of the fastest-growing parts of Bay County, with steady new-resident demand for local services — exactly the kind of market where ranking first pays off.'],
                    ['question' => 'Do you work with trades and home-service businesses in Lynn Haven?', 'answer' => 'Yes. Contractors, lawn care, HVAC, and remodelers are a core focus. We build content that ranks for "Lynn Haven" plus the specific services you provide.'],
                    ['question' => 'Can you help a medical or professional practice here?', 'answer' => 'Yes. We build patient- and client-friendly sites tuned for the families and retirees who choose providers close to home in Lynn Haven and north Bay County.'],
                ],
            ],
            'mexico-beach' => [
                'body' => <<<'HTML'
<h2>A small-town comeback worth marketing right</h2>
<p>Mexico Beach is rebuilding into something special — quieter than PCB, with a loyal returning crowd and a tight-knit business community. The families who book here come back year after year, and the businesses that win are the ones that reflect that slower, smaller, repeat-visitor character honestly. We will not try to make Mexico Beach look like Panama City Beach, because that is not what your customers are coming to find.</p>
<p>From the canal to Highway 98, the search demand here is real but specific. Visitors plan their trips early and locals choose carefully where to spend. We build sites and SEO that put you in front of both — the family researching a canal-side rental and the resident picking where to eat.</p>
<h2>Repeat visitors and rebuilding locals</h2>
<p>Vacation rental owners, restaurants, charter captains, and the trades rebuilding the community all share one thing: a customer who values the real Mexico Beach. We build content that speaks to that loyalty rather than chasing the spring-break crowd.</p>
HTML,
                'faqs' => [
                    ['question' => 'Mexico Beach is small. Can SEO really help?', 'answer' => 'Yes. Smaller markets often have less competition, so a well-built, well-optimized site can dominate the searches your loyal, returning customers actually use.'],
                    ['question' => 'Do you work with vacation rental owners in Mexico Beach?', 'answer' => 'Yes. We build property sites that capture the families who return to Mexico Beach year after year, optimized for the searches they run when planning their trip.'],
                    ['question' => 'Can you help the trades rebuilding the community?', 'answer' => 'Yes. Contractors, cleaners, and local trades get year-round local SEO aimed at residents and second-home owners investing in Mexico Beach.'],
                ],
            ],
            'seaside' => [
                'body' => <<<'HTML'
<h2>The town that set the standard</h2>
<p>Seaside set the design bar the rest of 30A is still chasing, and your customers feel it. Boutique shoppers, design-conscious families, and second-home owners walking Central Square expect a website that lives up to the storefront. A generic real-estate-template site undercuts the brand you have worked to build. We design sites that feel like an extension of Seaside itself — pastel-clean, walkable, hand-built.</p>
<p>Seaside also draws a high volume of visitors comparing options before they arrive. We make sure your site loads instantly, reflects your brand, and ranks for the shopping, dining, and rental searches that bring the right kind of traffic to your door.</p>
<h2>Respecting the brand of the town</h2>
<p>Working with Seaside businesses means respecting the town's identity as much as your own. We build sites that earn their place on Central Square and rank up and down 30A.</p>
HTML,
                'faqs' => [
                    ['question' => 'Can you match the Seaside design standard?', 'answer' => 'Yes — that is exactly what we do. We build sites that feel like an extension of your Central Square storefront, not a generic template, while still ranking and converting.'],
                    ['question' => 'Do you work with Seaside boutiques and galleries?', 'answer' => 'Yes. On-brand sites that match the Seaside aesthetic and rank for shopping searches up and down 30A are a core focus.'],
                    ['question' => 'Can you help a Seaside cottage rental manager?', 'answer' => 'Yes. We build polished property pages with SEO for the specific cottages you manage, tuned to the expectations of the Seaside guest.'],
                ],
            ],
            'watercolor' => [
                'body' => <<<'HTML'
<h2>Design-led, like WaterColor itself</h2>
<p>WaterColor guests come for the dune lake, the BeachClub, and a design-led vacation you cannot fake. The businesses serving that customer need websites with the same polish. Search results for "30A vacation rental" are full of generic templates; standing apart means a site that reflects the WaterColor standard and ranks for the searches that actually convert here.</p>
<p>The WaterColor customer is distinct from a Seacrest renter or a Destin condo guest — different price point, different expectations, different search behavior. We build around that specific buyer instead of treating all of 30A as one undifferentiated market.</p>
<h2>From Western Lake to the BeachClub</h2>
<p>Rental managers, lifestyle shops, restaurants, and design pros here all serve a guest who notices detail. We build the content and structure that win the WaterColor-specific searches rather than competing only on broad, expensive 30A terms.</p>
HTML,
                'faqs' => [
                    ['question' => 'How is marketing WaterColor different from the rest of 30A?', 'answer' => 'The WaterColor customer has a specific price point and expectation. We build around that buyer and the WaterColor-specific searches, instead of lumping you into generic "30A" terms.'],
                    ['question' => 'Do you build rental sites for WaterColor properties?', 'answer' => 'Yes. We build property and listing pages optimized for WaterColor cottages, homes, and BeachClub-access rentals, with the polish guests expect.'],
                    ['question' => 'Can you help a boutique or lifestyle shop in WaterColor?', 'answer' => 'Yes. On-brand sites that match the WaterColor design standard and pull in shoppers from across 30A are exactly what we build.'],
                ],
            ],
            'rosemary-beach' => [
                'body' => <<<'HTML'
<h2>A look you can spot from a block away</h2>
<p>Rosemary Beach has a distinctive identity — Dutch West Indies architecture, narrow walkways, deep porches, and a customer who notices the difference. Your website should hold up to that same level of detail. We build sites that match Rosemary's storefront standard and rank for the east-end-of-30A searches that bring the right guests through your door.</p>
<p>Rosemary is not just another beach town, and your marketing should not treat it like one. The design sensibility, price point, and customer it attracts are specific. We build around that buyer rather than a generic 30A template, so your site reflects what makes Rosemary, Rosemary.</p>
<h2>From Town Hall to the boardwalks</h2>
<p>Boutiques, wine bars, rental managers, and design pros here all serve a discerning customer. We build the on-brand site and town-specific content that win in one of 30A's most premium pockets.</p>
HTML,
                'faqs' => [
                    ['question' => 'Can your sites match Rosemary Beach\'s distinctive style?', 'answer' => 'Yes. We build sites that hold up to Rosemary\'s architectural and storefront standard, not a generic beach template, while still ranking and converting.'],
                    ['question' => 'Do you work with Rosemary Beach restaurants and wine bars?', 'answer' => 'Yes. Menus, reservations, and Google Business Profile work tuned for the locals-and-visitors mix Rosemary spots depend on are part of what we do.'],
                    ['question' => 'Is Rosemary Beach competitive for rentals?', 'answer' => 'It is one of 30A\'s most premium pockets. We build polished property sites optimized for the specific homes you manage to compete for that high-end guest.'],
                ],
            ],
            'alys-beach' => [
                'body' => <<<'HTML'
<h2>Restraint that matches the architecture</h2>
<p>Alys Beach is unlike anything else on 30A — all-white architecture, courtyards, and a level of design discipline you only get when an entire town is built to one standard. A business here cannot afford to look like every other beach-town site on the results page. We build sites with the restraint and polish the Alys customer expects, so yours looks like it belongs across the courtyard from Caliza.</p>
<p>The Alys guest is design-aware and detail-oriented, and they research carefully. We make sure your site reflects that sensibility, loads instantly, and ranks for the specific searches your guests are actually running — not just broad, generic terms.</p>
<h2>Considered, not loud</h2>
<p>Private homes, fine dining, design retail, and the architects and agents who work here all share a customer who values restraint. We build sites that compete on experience and detail in one of the country's most design-aware markets.</p>
HTML,
                'faqs' => [
                    ['question' => 'Can you build a site that fits the Alys Beach aesthetic?', 'answer' => 'Yes. Restraint and polish are the point — we build sites that look at home next to the Alys architecture rather than a loud, generic beach template.'],
                    ['question' => 'Do you work with Alys Beach rentals and private homes?', 'answer' => 'Yes. We build property pages with the restraint and detail the Alys guest expects, optimized for the searches that convert at this price point.'],
                    ['question' => 'Can you help architects, designers, and agents in Alys Beach?', 'answer' => 'Yes. Studio and agent sites built to compete in one of the country\'s most design-aware markets are a natural fit for what we do.'],
                ],
            ],
        ];
    }
}
