<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\DataTables\GovernoratesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Governorate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GovernoratesController extends Controller
{
    public function index(GovernoratesDataTable $dataTable)
    {
        return $dataTable->render('admin.locations.governorates.index');
    }

    public function create(): View
    {
        $countries = Country::query()->orderBy('name')->pluck('name','id');
        return view('admin.locations.governorates.create', compact('countries'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'country_id' => ['required','exists:countries,id'],
            'name' => ['required','string','max:190','unique:governorates,name,NULL,id,country_id,' . $request->country_id],
        ]);
        Governorate::create($data);
        return redirect()->route('admin.governorates.index')->with('status', __('messages.success_created'));
    }

    public function edit(Governorate $governorate): View
    {
        $countries = Country::query()->orderBy('name')->pluck('name','id');
        return view('admin.locations.governorates.edit', compact('governorate','countries'));
    }

    public function update(Request $request, Governorate $governorate): RedirectResponse
    {
        $data = $request->validate([
            'country_id' => ['required','exists:countries,id'],
            'name' => ['required','string','max:190','unique:governorates,name,' . $governorate->id . ',id,country_id,' . $request->country_id],
        ]);
        $governorate->update($data);
        return redirect()->route('admin.governorates.index')->with('status', __('messages.success_updated'));
    }

    public function destroy(Governorate $governorate): RedirectResponse
    {
        if ($governorate->states()->exists()) {
            return back()->withErrors(__('locations.delete_governorate_has_children'));
        }
        $governorate->delete();
        return redirect()->route('admin.governorates.index')->with('status', __('messages.success_deleted'));
    }
}

