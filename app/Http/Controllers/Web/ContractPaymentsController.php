<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContractPayments\StoreContractPaymentRequest;
use App\Http\Requests\ContractPayments\UpdateContractPaymentRequest;
use App\DataTables\ContractPaymentsDataTable;
use App\Models\ContractPayment;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContractPaymentsController extends Controller
{
    public function index(ContractPaymentsDataTable $dataTable)
    {
        return $dataTable->render('admin.contract_payments.index');
    }

    public function create(): View
    {
        $properties = Property::forCurrentUser()->pluck('name','id');
        $tenants = Tenant::query()->pluck('full_name','id');
        $units = Unit::query()->select('id','name','property_id','rent_amount')->get();
        return view('admin.contract_payments.create', compact('properties','tenants','units'));
    }

    public function store(StoreContractPaymentRequest $request): RedirectResponse
    {
        ContractPayment::create($request->validated());
        return redirect()->route('admin.contract-payments.index')->with('status', __('messages.success_created'));
    }

    public function edit(ContractPayment $contractPayment): View
    {
        $properties = Property::forCurrentUser()->pluck('name','id');
        $tenants = Tenant::query()->pluck('full_name','id');
        $units = Unit::query()->select('id','name','property_id','rent_amount')->get();
        return view('admin.contract_payments.edit', compact('contractPayment','properties','tenants','units'));
    }

    public function update(UpdateContractPaymentRequest $request, ContractPayment $contractPayment): RedirectResponse
    {
        $contractPayment->update($request->validated());
        return redirect()->route('admin.contract-payments.index')->with('status', __('messages.success_updated'));
    }

    public function destroy(ContractPayment $contractPayment): RedirectResponse
    {
        $contractPayment->delete();
        return redirect()->route('admin.contract-payments.index')->with('status', __('messages.success_deleted'));
    }
}
