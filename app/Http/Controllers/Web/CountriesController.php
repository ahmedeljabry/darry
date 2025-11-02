<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\DataTables\CountriesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CountriesController extends Controller
{
    public function index(CountriesDataTable $dataTable)
    {
        return $dataTable->render('admin.locations.countries.index');
    }

    public function create(): View
    {
        return view('admin.locations.countries.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:190','unique:countries,name'],
            'code' => ['nullable','string','max:10','unique:countries,code'],
        ]);
        Country::create($data);
        return redirect()->route('admin.countries.index')->with('status', __('messages.success_created'));
    }

    public function edit(Country $country): View
    {
        return view('admin.locations.countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:190','unique:countries,name,' . $country->id],
            'code' => ['nullable','string','max:10','unique:countries,code,' . $country->id],
        ]);
        $country->update($data);
        return redirect()->route('admin.countries.index')->with('status', __('messages.success_updated'));
    }

    public function destroy(Country $country): RedirectResponse
    {
        if ($country->governorates()->exists()) {
            return back()->withErrors(__('locations.delete_country_has_children'));
        }
        $country->delete();
        return redirect()->route('admin.countries.index')->with('status', __('messages.success_deleted'));
    }
}

