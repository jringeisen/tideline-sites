<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->services() as $sortOrder => $service) {
            Service::query()->updateOrCreate(
                ['slug' => $service['slug']],
                [
                    'name' => $service['name'],
                    'summary' => $service['summary'],
                    'icon' => $service['icon'],
                    'hero_subhead' => $service['hero_subhead'] ?? null,
                    'sort_order' => $sortOrder,
                    'is_published' => true,
                ],
            );
        }
    }

    /**
     * Initial service catalogue, migrated from the homepage services array.
     *
     * @return list<array<string, string|null>>
     */
    private function services(): array
    {
        return [
            [
                'slug' => 'web-design',
                'name' => 'Web Design',
                'summary' => 'Hand-crafted, lightning-fast websites built to convert visitors into customers.',
                'hero_subhead' => 'Custom websites designed to load fast, look the part, and turn visitors into paying customers.',
                'icon' => 'M3 4.5A1.5 1.5 0 014.5 3h11A1.5 1.5 0 0117 4.5v8a1.5 1.5 0 01-1.5 1.5h-4l.4 2H13a.75.75 0 010 1.5H7a.75.75 0 010-1.5h1.1l.4-2h-4A1.5 1.5 0 013 12.5v-8z',
            ],
            [
                'slug' => 'seo-optimization',
                'name' => 'SEO Optimization',
                'summary' => 'On-page SEO, local listings, and ongoing optimization so the right people find you on Google.',
                'hero_subhead' => 'On-page SEO, local listings, and ongoing optimization that put your business in front of the customers already searching for you.',
                'icon' => 'M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.45 4.39l3.08 3.08a.75.75 0 11-1.06 1.06l-3.08-3.08A7 7 0 012 9z',
            ],
            [
                'slug' => 'blog-writing',
                'name' => 'Blog Writing',
                'summary' => 'Original articles that rank, educate your customers, and turn your site into a magnet for search traffic.',
                'hero_subhead' => 'Original, search-optimized articles that pull traffic into your site and turn readers into customers.',
                'icon' => 'M3.5 3A1.5 1.5 0 002 4.5v11A1.5 1.5 0 003.5 17h13a1.5 1.5 0 001.5-1.5v-11A1.5 1.5 0 0016.5 3h-13zM5 7.25A.75.75 0 015.75 6.5h8.5a.75.75 0 010 1.5h-8.5A.75.75 0 015 7.25zM5.75 10a.75.75 0 000 1.5h8.5a.75.75 0 000-1.5h-8.5zM5 13.75a.75.75 0 01.75-.75h5.5a.75.75 0 010 1.5h-5.5a.75.75 0 01-.75-.75z',
            ],
            [
                'slug' => 'newsletters',
                'name' => 'Newsletters',
                'summary' => 'Beautifully designed monthly newsletters that keep your business top-of-mind with past customers.',
                'hero_subhead' => 'Beautifully designed monthly emails that keep your business top-of-mind and bring past customers back.',
                'icon' => 'M2.5 4A1.5 1.5 0 001 5.5v9A1.5 1.5 0 002.5 16h15a1.5 1.5 0 001.5-1.5v-9A1.5 1.5 0 0017.5 4h-15zM3 6.18l6.4 4.27a1.5 1.5 0 001.66 0L17.5 6.18V14.5H3V6.18zM16.1 5.5L10 9.57 3.9 5.5h12.2z',
            ],
        ];
    }
}
