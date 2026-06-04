<?php

namespace App\Http\Controllers;

use App\Support\MarketingSchema;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LocationController extends Controller
{
    /**
     * Display the locations index — every Gulf Coast town we serve.
     */
    public function index(): Response
    {
        $locations = array_values(array_map(
            fn (array $location): array => [
                'slug' => $location['slug'],
                'name' => $location['name'],
                'display_name' => $location['display_name'],
                'hero_subhead' => $location['hero_subhead'],
            ],
            config('locations', []),
        ));

        return Inertia::render('Locations', [
            'locations' => $locations,
            'meta' => [
                'title' => 'Service Area Locations — Gulf Coast Web Design & SEO',
                'description' => "Web design and SEO for small businesses across Florida's Gulf Coast — from Panama City Beach and Destin to 30A, Seaside, and Rosemary Beach.",
                'canonical' => url()->current(),
            ],
            'schema' => [
                MarketingSchema::breadcrumb([
                    ['name' => 'Home', 'item' => url('/')],
                    ['name' => 'Locations', 'item' => url()->current()],
                ]),
            ],
        ]);
    }

    public function show(string $slug): Response
    {
        $location = config("locations.{$slug}");

        if (! $location) {
            throw new NotFoundHttpException;
        }

        // Resolve nearby towns to a link only when the target has its own page.
        $nearby = array_map(function (array $pair): array {
            [$name, $nearbySlug] = $pair;

            return [
                'name' => $name,
                'slug' => $nearbySlug && config("locations.{$nearbySlug}") ? $nearbySlug : null,
            ];
        }, $location['nearby'] ?? []);

        $schema = [
            MarketingSchema::localBusinessForLocation($location),
            MarketingSchema::breadcrumb([
                ['name' => 'Home', 'item' => url('/')],
                ['name' => 'Locations', 'item' => route('locations.index')],
                ['name' => $location['name'], 'item' => url()->current()],
            ]),
        ];

        if (! empty($location['faqs'])) {
            $schema[] = MarketingSchema::faqPage($location['faqs']);
        }

        return Inertia::render('Location', [
            'location' => $location,
            'nearby' => $nearby,
            'meta' => [
                'title' => "{$location['name']} Web Design & SEO — All American Web Design",
                'description' => $location['meta_description'],
                'canonical' => url()->current(),
            ],
            'schema' => $schema,
        ]);
    }
}
