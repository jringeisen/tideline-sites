<?php

namespace App\Http\Controllers;

use App\Support\MarketingSchema;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceController extends Controller
{
    /**
     * Display the services index.
     */
    public function index(): Response
    {
        $services = array_values(array_map(
            fn (array $service): array => [
                'slug' => $service['slug'],
                'name' => $service['name'],
                'description' => $service['description'],
                'icon' => $service['icon'],
                'tagline' => $service['tagline'],
            ],
            config('offerings'),
        ));

        return Inertia::render('Services', [
            'services' => $services,
            'meta' => [
                'title' => 'Services — Web Design, SEO, Blog Writing & Newsletters',
                'description' => 'Web design, local SEO, blog writing, and newsletters for Gulf Coast small businesses. Veteran-owned, built in America. Plans from $299/month.',
                'canonical' => url()->current(),
            ],
            'schema' => [
                MarketingSchema::servicesItemList($services),
                MarketingSchema::breadcrumb([
                    ['name' => 'Home', 'item' => url('/')],
                    ['name' => 'Services', 'item' => url()->current()],
                ]),
            ],
        ]);
    }

    /**
     * Display a single service detail page.
     */
    public function show(string $slug): Response
    {
        $service = config("offerings.{$slug}");

        if (! $service) {
            throw new NotFoundHttpException;
        }

        $schema = [
            MarketingSchema::service($service),
            MarketingSchema::breadcrumb([
                ['name' => 'Home', 'item' => url('/')],
                ['name' => 'Services', 'item' => route('services.index')],
                ['name' => $service['name'], 'item' => url()->current()],
            ]),
        ];

        if (! empty($service['faqs'])) {
            $schema[] = MarketingSchema::faqPage($service['faqs']);
        }

        return Inertia::render('Service', [
            'service' => $service,
            'meta' => [
                'title' => "{$service['name']} — All American Web Design",
                'description' => $service['intro'],
                'canonical' => url()->current(),
            ],
            'schema' => $schema,
        ]);
    }
}
