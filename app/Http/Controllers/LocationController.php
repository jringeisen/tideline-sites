<?php

namespace App\Http\Controllers;

use App\Support\MarketingSchema;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LocationController extends Controller
{
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

        return Inertia::render('Location', [
            'location' => $location,
            'nearby' => $nearby,
            'meta' => [
                'title' => "{$location['name']} Web Design & SEO — All American Web Design",
                'description' => $location['meta_description'],
                'canonical' => url()->current(),
            ],
            'schema' => [
                MarketingSchema::localBusinessForLocation($location),
                MarketingSchema::breadcrumb([
                    ['name' => 'Home', 'item' => url('/')],
                    ['name' => 'Locations', 'item' => url('/locations')],
                    ['name' => $location['name'], 'item' => url()->current()],
                ]),
            ],
        ]);
    }
}
