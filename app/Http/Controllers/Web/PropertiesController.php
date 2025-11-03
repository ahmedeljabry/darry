<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Facility;
use App\Models\Unit;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\State;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Properties\StorePropertyRequest;
use App\Http\Requests\Properties\UpdatePropertyRequest;
use Illuminate\View\View;
use App\DataTables\PropertiesDataTable;
use App\Support\PropertyAccess;
use Illuminate\Support\Facades\Storage;
use App\Domain\Enums\UnitType;
use App\Domain\Enums\RentType;
use App\Domain\Enums\UnitStatus;

class PropertiesController extends Controller
{
    public function index(PropertiesDataTable $dataTable)
    {
        return $dataTable->render('admin.properties.index');
    }

    public function create(): View
    {
        if (PropertyAccess::currentId()) {
            abort(403);
        }

        $facilities = Facility::query()->pluck('name','id');
        $countries = Country::query()->orderBy('name')->pluck('name','id')->toArray();

        return view('admin.properties.create', compact('facilities','countries'));
    }

    public function show(Property $property): View
    {
        PropertyAccess::ensureProperty($property);

        $property->load([
            'facilities:id,name',
            'floors',
            'units' => fn ($query) => $query->with('parent')->orderBy('name'),
        ]);

        $unitTypes = UnitType::cases();
        $rentTypes = RentType::cases();
        $unitStatuses = UnitStatus::cases();
        $parentUnits = Unit::query()
            ->where('property_id', $property->id)
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('admin.properties.show', [
            'property' => $property,
            'unitTypes' => $unitTypes,
            'rentTypes' => $rentTypes,
            'unitStatuses' => $unitStatuses,
            'parentUnits' => $parentUnits,
        ]);
    }

    public function store(StorePropertyRequest $request): RedirectResponse
    {
        if (PropertyAccess::currentId()) {
            abort(403);
        }

        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('properties','public');
        }
        if ($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $img) {
                $paths[] = $img->store('properties','public');
            }
            $data['images'] = $paths;
        }

        $data = $this->resolveLocationValues($data);

        $property = Property::create($data);
        $property->facilities()->sync($data['facilities'] ?? []);

        return redirect()->route('admin.properties.index')->with('status', __('messages.success_created'));
    }

    public function edit(Property $property): View
    {
        PropertyAccess::ensureProperty($property);

        $facilities = Facility::query()->pluck('name','id');
        $countries = Country::query()->orderBy('name')->pluck('name','id')->toArray();

        $selectedCountryId = $property->country ? Country::query()->where('name', $property->country)->value('id') : null;
        $governorates = $selectedCountryId
            ? Governorate::query()->where('country_id', $selectedCountryId)->orderBy('name')->pluck('name','id')->toArray()
            : [];
        $selectedGovernorateId = $property->governorate
            ? Governorate::query()
                ->where('country_id', $selectedCountryId)
                ->where('name', $property->governorate)
                ->value('id')
            : null;
        $states = $selectedGovernorateId
            ? State::query()->where('governorate_id', $selectedGovernorateId)->orderBy('name')->pluck('name','id')->toArray()
            : [];
        $selectedStateId = $property->state
            ? State::query()
                ->where('governorate_id', $selectedGovernorateId)
                ->where('name', $property->state)
                ->value('id')
            : null;

        return view('admin.properties.edit', [
            'property' => $property,
            'facilities' => $facilities,
            'countries' => $countries,
            'governorates' => $governorates,
            'states' => $states,
            'selectedCountryId' => $selectedCountryId,
            'selectedGovernorateId' => $selectedGovernorateId,
            'selectedStateId' => $selectedStateId,
        ]);
    }

    public function update(UpdatePropertyRequest $request, Property $property): RedirectResponse
    {
        PropertyAccess::ensureProperty($property);

        $data = $request->validated();

        // Start with existing images array
        $currentImages = is_array($property->images) ? $property->images : [];

        // Handle deleting existing thumbnail
        if ($request->filled('delete_thumbnail') && $property->thumbnail) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($property->thumbnail);
            $data['thumbnail'] = null;
        }

        // Handle deleting specific existing images
        $deleteImages = $request->input('delete_images', []);
        if (!empty($deleteImages)) {
            foreach ($deleteImages as $p) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($p);
            }
            $currentImages = array_values(array_diff($currentImages, $deleteImages));
        }

        // Upload new thumbnail (replaces if provided)
        if ($request->hasFile('thumbnail')) {
            if ($property->thumbnail) {
                Storage::disk('public')->delete($property->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('properties','public');
        }

        // Upload new images (append)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $currentImages[] = $img->store('properties','public');
            }
        }
        $data['images'] = $currentImages;

        $data = $this->resolveLocationValues($data);

        $property->update($data);
        $property->facilities()->sync($data['facilities'] ?? []);

        return redirect()->route('admin.properties.index')->with('status', __('messages.success_updated'));
    }

    public function destroy(Property $property): RedirectResponse
    {
        PropertyAccess::ensureProperty($property);

        if ($property->thumbnail) {
            Storage::disk('public')->delete($property->thumbnail);
        }
        $imgs = is_array($property->images) ? $property->images : [];
        foreach ($imgs as $p) {
            Storage::disk('public')->delete($p);
        }
        $property->delete();
        return redirect()->route('admin.properties.index')->with('status', __('messages.success_deleted'));
    }

    private function resolveLocationValues(array $data): array
    {
        $countryId = $data['country_id'] ?? null;
        $governorateId = $data['governorate_id'] ?? null;
        $stateId = $data['state_id'] ?? null;

        if ($countryId) {
            if ($country = Country::query()->find($countryId)) {
                $data['country'] = $country->name;
            }
        }
        if ($governorateId) {
            if ($governorate = Governorate::query()->find($governorateId)) {
                $data['governorate'] = $governorate->name;
            }
        }
        if ($stateId) {
            if ($state = State::query()->find($stateId)) {
                $data['state'] = $state->name;
            }
        }

        unset($data['country_id'], $data['governorate_id'], $data['state_id']);

        return $data;
    }
}
