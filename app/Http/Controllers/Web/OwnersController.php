<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\DataTables\OwnersDataTable;
use App\Http\Controllers\Controller;
use App\Models\Owner;
use App\Models\Property;
use App\Support\PropertyAccess;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OwnersController extends Controller
{
    public function index(OwnersDataTable $dataTable)
    {
        return $dataTable->render('admin.owners.index');
    }

    public function create(): View
    {
        return view('admin.owners.create', $this->propertyFormContext());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateOwner($request);
        Owner::create($data);
        return redirect()->route('admin.owners.index')->with('status', __('messages.success_created'));
    }

    public function edit(Owner $owner): View
    {
        return view('admin.owners.edit', array_merge(
            ['owner' => $owner],
            $this->propertyFormContext()
        ));
    }

    public function update(Request $request, Owner $owner): RedirectResponse
    {
        $data = $this->validateOwner($request, $owner);
        $owner->update($data);
        return redirect()->route('admin.owners.index')->with('status', __('messages.success_updated'));
    }

    public function destroy(Owner $owner): RedirectResponse
    {
        $owner->delete();
        return redirect()->route('admin.owners.index')->with('status', __('messages.success_deleted'));
    }

    private function validateOwner(Request $request, ?Owner $owner = null): array
    {
        if ($forcedPropertyId = PropertyAccess::currentId()) {
            $request->merge(['property_id' => $forcedPropertyId]);
        }

        $rules = [
            'property_id' => ['required','integer','exists:properties,id'],
            'full_name' => ['required','string','max:255'],
            'id_or_cr' => ['nullable','string','max:190'],
            'email' => ['nullable','email','max:255'],
            'phone' => ['nullable','string','max:50'],
            'address' => ['nullable','string','max:500'],
            'owner_type' => ['nullable','in:PERSONAL,COMMERCIAL'],
            'status' => ['required','in:ACTIVE,INACTIVE'],
        ];

        if ($forcedPropertyId = PropertyAccess::currentId()) {
            $rules['property_id'][] = Rule::in([$forcedPropertyId]);
        }

        if ($owner && !$request->filled('property_id') && $owner->property_id) {
            $request->merge(['property_id' => $owner->property_id]);
        }

        return $request->validate($rules);
    }

    private function propertyFormContext(): array
    {
        $propertiesCollection = Property::forCurrentUser()->pluck('name', 'id');
        $currentPropertyId = PropertyAccess::currentId();
        $canManageSystem = $currentPropertyId === null;
        $properties = $canManageSystem
            ? $propertiesCollection->toArray()
            : $propertiesCollection->only([$currentPropertyId])->toArray();

        return [
            'properties' => $properties,
            'canManageSystem' => $canManageSystem,
            'currentPropertyId' => $currentPropertyId,
            'currentPropertyName' => $currentPropertyId ? $propertiesCollection->get($currentPropertyId) : null,
        ];
    }
}
