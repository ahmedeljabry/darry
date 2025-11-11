<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\StoreTenantRequest;
use App\Http\Requests\Tenants\UpdateTenantRequest;
use App\Models\Property;
use App\Models\Tenant;
use App\Services\TenantsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\DataTables\TenantsDataTable;

class TenantsController extends Controller
{
    public function __construct(private readonly TenantsService $tenants)
    {
    }

    public function index(TenantsDataTable $dataTable)
    {
        return $dataTable->render('admin.tenants.index');
    }

    public function create(): View
    {
        return view('admin.tenants.create', $this->propertyFormContext());
    }

    public function store(StoreTenantRequest $request): RedirectResponse
    {
        $this->tenants->create($request->validated());
        return redirect()->route('admin.tenants.index')->with('status', __('messages.success_created'));
    }

    public function edit(Tenant $tenant): View
    {
        return view('admin.tenants.edit', array_merge(
            ['tenant' => $tenant],
            $this->propertyFormContext()
        ));
    }

    public function show(Tenant $tenant): View
    {
        $tenant->load(['relatives','property']);
        return view('admin.tenants.show', compact('tenant'));
    }

    public function update(UpdateTenantRequest $request, Tenant $tenant): RedirectResponse
    {
        $this->tenants->update($tenant, $request->validated());
        return redirect()->route('admin.tenants.index')->with('status', __('messages.success_updated'));
    }

    public function destroy(Tenant $tenant): RedirectResponse
    {
        $this->tenants->delete($tenant);
        return redirect()->route('admin.tenants.index')->with('status', __('messages.success_deleted'));
    }

    private function propertyFormContext(): array
    {
        $propertiesCollection = Property::forCurrentUser()->pluck('name', 'id');
        $currentPropertyId = Auth::user()?->property_id;
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
