<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contracts\StoreContractRequest;
use App\Http\Requests\Contracts\UpdateContractRequest;
use App\Models\Contract;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\DataTables\ContractsDataTable;
use Illuminate\Support\Str;

class ContractsController extends Controller
{
    public function index(ContractsDataTable $dataTable)
    {
        return $dataTable->render('admin.contracts.index');
    }

    public function create(): View
    {
        $properties = Property::query()->pluck('name','id');
        $tenants = Tenant::query()->pluck('full_name','id');
        $units = Unit::query()->select('id','name','property_id','rent_amount')->get();
        $unitsPayload = $units->map(static function (Unit $unit) {
            return [
                'id' => (string) $unit->id,
                'name' => $unit->name,
                'property_id' => (string) $unit->property_id,
                'rent_amount' => $unit->rent_amount,
            ];
        })->values();
        return view('admin.contracts.create', [
            'properties' => $properties,
            'tenants' => $tenants,
            'units' => $units,
            'unitsPayload' => $unitsPayload,
        ]);
    }

    public function store(StoreContractRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (empty($data['contract_no'])) {
            $data['contract_no'] = 'CTR-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        }
        if (!empty($data['start_date']) && !empty($data['duration_months'])) {
            $start = \Carbon\Carbon::parse($data['start_date']);
            $data['end_date'] = $start->copy()->addMonths((int) $data['duration_months'])->subDay()->toDateString();
        }
        if (empty($data['rent_amount'])) {
            $unit = Unit::find($data['unit_id']);
            if ($unit) { $data['rent_amount'] = $unit->rent_amount; }
        }
        Contract::create($data);
        return redirect()->route('admin.contracts.index')->with('status', __('messages.success_created'));
    }

    public function edit(Contract $contract): View
    {
        $properties = Property::query()->pluck('name','id');
        $tenants = Tenant::query()->pluck('full_name','id');
        $units = Unit::query()->select('id','name','property_id','rent_amount')->get();
        $unitsPayload = $units->map(static function (Unit $unit) {
            return [
                'id' => (string) $unit->id,
                'name' => $unit->name,
                'property_id' => (string) $unit->property_id,
                'rent_amount' => $unit->rent_amount,
            ];
        })->values();
        return view('admin.contracts.edit', [
            'contract' => $contract,
            'properties' => $properties,
            'tenants' => $tenants,
            'units' => $units,
            'unitsPayload' => $unitsPayload,
        ]);
    }

    public function update(UpdateContractRequest $request, Contract $contract): RedirectResponse
    {
        $data = $request->validated();
        if (!empty($data['start_date']) && !empty($data['duration_months'])) {
            $start = \Carbon\Carbon::parse($data['start_date']);
            $data['end_date'] = $start->copy()->addMonths((int) $data['duration_months'])->subDay()->toDateString();
        }
        if (empty($data['rent_amount']) && isset($data['unit_id'])) {
            $unit = Unit::find($data['unit_id']);
            if ($unit) { $data['rent_amount'] = $unit->rent_amount; }
        }
        $contract->update($data);
        return redirect()->route('admin.contracts.index')->with('status', __('messages.success_updated'));
    }

    public function destroy(Contract $contract): RedirectResponse
    {
        $contract->delete();
        return redirect()->route('admin.contracts.index')->with('status', __('messages.success_deleted'));
    }
}
