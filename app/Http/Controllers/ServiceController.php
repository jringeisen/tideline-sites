<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('services.index', [
            'services' => Service::published()->orderBy('sort_order')->get(),
        ]);
    }

    public function show(Service $service): View
    {
        if (! $service->is_published) {
            throw new NotFoundHttpException;
        }

        $service->load(['locations' => fn ($query) => $query->published()]);

        return view('services.show', [
            'service' => $service,
        ]);
    }
}
