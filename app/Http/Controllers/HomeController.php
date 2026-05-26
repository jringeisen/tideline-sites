<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function show(): View
    {
        return view('home', [
            'services' => Service::published()->orderBy('sort_order')->get(),
        ]);
    }
}
