<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Facilities\StoreFacilityRequest;
use App\Http\Requests\Facilities\UpdateFacilityRequest;
use Illuminate\View\View;
use App\DataTables\FacilitiesDataTable;

class FacilitiesController extends Controller
{
    public function index(FacilitiesDataTable $dataTable)
    {
        return $dataTable->render('admin.facilities.index');
    }

    public function create(): View
    {
        return view('admin.facilities.create');
    }

    public function store(StoreFacilityRequest $request): RedirectResponse
    {
        $data = $request->validated();
        Facility::create($data);
        return redirect()->route('admin.facilities.index')->with('status', __('messages.success_created'));
    }

    public function edit(Facility $facility): View
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(UpdateFacilityRequest $request, Facility $facility): RedirectResponse
    {
        $data = $request->validated();
        $facility->update($data);
        return redirect()->route('admin.facilities.index')->with('status', __('messages.success_updated'));
    }

    public function destroy(Facility $facility): RedirectResponse
    {
        $facility->delete();
        return redirect()->route('admin.facilities.index')->with('status', __('messages.success_deleted'));
    }
}
