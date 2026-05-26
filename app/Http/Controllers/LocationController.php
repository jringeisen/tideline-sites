<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LocationController extends Controller
{
    public function index(): View
    {
        return view('locations.index', [
            'locations' => Location::published()->orderBy('sort_order')->get(),
        ]);
    }

    public function show(Location $location): View
    {
        if (! $location->is_published) {
            throw new NotFoundHttpException;
        }

        $location->load([
            'services' => fn ($query) => $query->published(),
            'nearby' => fn ($query) => $query->published(),
        ]);

        return view('location', [
            'location' => $location,
        ]);
    }
}
