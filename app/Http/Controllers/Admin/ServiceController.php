<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Models\Location;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Services/Index', [
            'services' => Service::query()->withCount('locations')->orderBy('sort_order')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Services/Create', [
            'locations' => Location::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $service = Service::create(collect($data)->except('locations')->all());
        $service->locations()->sync($this->pivot($data['locations'] ?? []));

        return redirect()
            ->route('admin.services.edit', $service)
            ->with('status', 'Service saved.');
    }

    public function edit(Service $service): Response
    {
        return Inertia::render('Admin/Services/Edit', [
            'service' => $service,
            'serviceLocationIds' => $service->locations()->pluck('locations.id'),
            'locations' => Location::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $data = $request->validated();

        $service->fill(collect($data)->except('locations')->all());
        $service->save();
        $service->locations()->sync($this->pivot($data['locations'] ?? []));

        return redirect()
            ->route('admin.services.edit', $service)
            ->with('status', 'Service updated.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('status', 'Service deleted.');
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
