<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\StoreTenantRequest;
use App\Http\Requests\Tenants\UpdateTenantRequest;
use App\Models\Tenant;
use App\Services\TenantsService;
use Illuminate\Http\RedirectResponse;
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
        return view('admin.tenants.create');
    }

    public function store(StoreTenantRequest $request): RedirectResponse
    {
        $this->tenants->create($request->validated());
        return redirect()->route('admin.tenants.index')->with('status', __('messages.success_created'));
    }

    public function edit(Tenant $tenant): View
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    public function show(Tenant $tenant): View
    {
        $tenant->load(['relatives']);
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
}
