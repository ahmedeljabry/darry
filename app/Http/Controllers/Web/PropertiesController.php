<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Facility;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Properties\StorePropertyRequest;
use App\Http\Requests\Properties\UpdatePropertyRequest;
use Illuminate\View\View;
use App\DataTables\PropertiesDataTable;
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
        $facilities = Facility::query()->pluck('name','id');
        return view('admin.properties.create', compact('facilities'));
    }

    public function show(Property $property): View
    {
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

        $property = Property::create($data);
        $property->facilities()->sync($data['facilities'] ?? []);

        return redirect()->route('admin.properties.index')->with('status', __('messages.success_created'));
    }

    public function edit(Property $property): View
    {
        $facilities = Facility::query()->pluck('name','id');
        return view('admin.properties.edit', compact('property','facilities'));
    }

    public function update(UpdatePropertyRequest $request, Property $property): RedirectResponse
    {
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

        $property->update($data);
        $property->facilities()->sync($data['facilities'] ?? []);

        return redirect()->route('admin.properties.index')->with('status', __('messages.success_updated'));
    }

    public function destroy(Property $property): RedirectResponse
    {
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
}
