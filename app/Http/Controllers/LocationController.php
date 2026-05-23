<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LocationController extends Controller
{
    public function show(string $slug): View
    {
        $location = config("locations.{$slug}");

        if (! $location) {
            throw new NotFoundHttpException;
        }

        return view('location', [
            'location' => $location,
        ]);
    }
}
