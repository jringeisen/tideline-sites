<?php

namespace App\Http\Controllers;

use App\Support\MarketingSchema;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    /**
     * Display the About page.
     */
    public function __invoke(): Response
    {
        return Inertia::render('About', [
            'meta' => [
                'title' => 'About All American Web Design — Family-owned & Veteran-owned',
                'description' => 'All American Web Design is a husband-and-wife, veteran-owned web design and SEO studio building custom websites for American small businesses. Meet Jon and Elena.',
                'canonical' => url()->current(),
            ],
            'schema' => [
                MarketingSchema::organization(),
                MarketingSchema::jonPerson(),
                MarketingSchema::elenaPerson(),
                MarketingSchema::breadcrumb([
                    ['name' => 'Home', 'item' => url('/')],
                    ['name' => 'About', 'item' => url()->current()],
                ]),
            ],
        ]);
    }
}
