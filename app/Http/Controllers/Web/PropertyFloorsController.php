<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Floors\StoreFloorRequest;
use App\Http\Requests\Floors\UpdateFloorRequest;
use App\Models\Floor;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;

class PropertyFloorsController extends Controller
{
    public function store(Property $property, StoreFloorRequest $request): RedirectResponse
    {
        $property->floors()->create($request->validated());

        return redirect()
            ->route('admin.properties.show', $property)
            ->with('status', __('messages.success_created'));
    }

    public function update(Property $property, Floor $floor, UpdateFloorRequest $request): RedirectResponse
    {
        abort_unless($floor->property_id === $property->id, 404);

        $floor->update($request->validated());

        return redirect()
            ->route('admin.properties.show', $property)
            ->with('status', __('messages.success_updated'));
    }

    public function destroy(Property $property, Floor $floor): RedirectResponse
    {
        abort_unless($floor->property_id === $property->id, 404);

        $floor->delete();

        return redirect()
            ->route('admin.properties.show', $property)
            ->with('status', __('messages.success_deleted'));
    }
}

