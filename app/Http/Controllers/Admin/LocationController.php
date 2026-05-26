<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLocationRequest;
use App\Http\Requests\Admin\UpdateLocationRequest;
use App\Models\Location;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class LocationController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Locations/Index', [
            'locations' => Location::query()->withCount('services')->orderBy('sort_order')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Locations/Create', $this->options());
    }

    public function store(StoreLocationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $location = Location::create(collect($data)->except(['nearby', 'services'])->all());
        $location->nearby()->sync($this->pivot($data['nearby'] ?? []));
        $location->services()->sync($this->pivot($data['services'] ?? []));

        return redirect()
            ->route('admin.locations.edit', $location)
            ->with('status', 'Location saved.');
    }

    public function edit(Location $location): Response
    {
        return Inertia::render('Admin/Locations/Edit', [
            'location' => $location,
            'locationNearbyIds' => $location->nearby()->pluck('locations.id'),
            'locationServiceIds' => $location->services()->pluck('services.id'),
            ...$this->options($location),
        ]);
    }

    public function update(UpdateLocationRequest $request, Location $location): RedirectResponse
    {
        $data = $request->validated();

        $location->fill(collect($data)->except(['nearby', 'services'])->all());
        $location->save();
        $location->nearby()->sync($this->pivot($data['nearby'] ?? []));
        $location->services()->sync($this->pivot($data['services'] ?? []));

        return redirect()
            ->route('admin.locations.edit', $location)
            ->with('status', 'Location updated.');
    }

    public function destroy(Location $location): RedirectResponse
    {
        $location->delete();

        return redirect()
            ->route('admin.locations.index')
            ->with('status', 'Location deleted.');
    }

    /**
     * Select options for the nearby/services pickers. Excludes the location
     * itself from the nearby choices when editing.
     *
     * @return array<string, mixed>
     */
    private function options(?Location $location = null): array
    {
        return [
            'allLocations' => Location::query()
                ->when($location, fn ($query) => $query->whereKeyNot($location->getKey()))
                ->orderBy('name')
                ->get(['id', 'name']),
            'allServices' => Service::query()->orderBy('sort_order')->get(['id', 'name']),
        ];
    }

    /**
     * Map an ordered list of ids to a sync payload that preserves order.
     *
     * @param  array<int, int>  $ids
     * @return array<int, array{sort_order: int}>
     */
    private function pivot(array $ids): array
    {
        return Collection::make($ids)
            ->values()
            ->mapWithKeys(fn (int $id, int $i): array => [$id => ['sort_order' => $i]])
            ->all();
    }
}
