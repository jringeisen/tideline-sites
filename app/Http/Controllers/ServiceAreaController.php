<?php

namespace App\Http\Controllers;

use App\Support\MarketingSchema;
use Inertia\Inertia;
use Inertia\Response;

class ServiceAreaController extends Controller
{
    /**
     * Display the Service Area page.
     */
    public function __invoke(): Response
    {
        $cities = [
            'Destin', 'Miramar Beach', 'Sandestin', 'Santa Rosa Beach',
            'Blue Mountain Beach', 'Grayton Beach', 'WaterColor', 'Seaside',
            'Seagrove Beach', 'WaterSound', 'Seacrest Beach', 'Alys Beach',
            'Rosemary Beach', 'Inlet Beach', 'Panama City Beach', 'Panama City',
        ];

        $cityLinks = [
            'Destin' => 'destin',
            'Santa Rosa Beach' => '30a',
            'Grayton Beach' => '30a',
            'WaterColor' => '30a',
            'Seaside' => '30a',
            'Seagrove Beach' => '30a',
            'WaterSound' => '30a',
            'Seacrest Beach' => '30a',
            'Alys Beach' => '30a',
            'Rosemary Beach' => '30a',
            'Inlet Beach' => '30a',
            'Panama City Beach' => 'panama-city-beach',
        ];

        $featuredLocations = [
            [
                'slug' => 'destin',
                'name' => 'Destin',
                'blurb' => 'From the Destin Harbor to Crab Island, websites for charter captains, beachfront restaurants, vacation rentals, and year-round local services.',
            ],
            [
                'slug' => '30a',
                'name' => '30A',
                'blurb' => 'Seaside, WaterColor, Alys, Rosemary, Inlet, and the rest of the 30A corridor, sites built for the boutique communities that define the brand.',
            ],
            [
                'slug' => 'panama-city-beach',
                'name' => 'Panama City Beach',
                'blurb' => 'From Pier Park to Front Beach Road, websites and SEO that help PCB businesses win year-round, not just during spring break.',
            ],
        ];

        return Inertia::render('ServiceArea', [
            'cities' => $cities,
            'cityLinks' => $cityLinks,
            'featuredLocations' => $featuredLocations,
            'meta' => [
                'title' => 'Service Area — All American Web Design | Custom Websites Nationwide',
                'description' => "All American Web Design builds custom websites for small businesses across the United States, and does hands-on local SEO on Florida's Gulf Coast, from Destin to Panama City Beach.",
                'canonical' => url()->current(),
            ],
            'schema' => [
                MarketingSchema::serviceArea($cities),
            ],
        ]);
    }
}
