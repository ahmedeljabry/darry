<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\DataTables\StatesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\State;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatesController extends Controller
{
    public function index(StatesDataTable $dataTable)
    {
        return $dataTable->render('admin.locations.states.index');
    }

    public function create(Request $request): View
    {
        $countries = Country::query()->orderBy('name')->pluck('name','id')->toArray();
        $selectedCountry = $request->old('country_id');
        if (!$selectedCountry && !empty($countries)) {
            $selectedCountry = array_key_first($countries);
        }
        $governorates = $selectedCountry
            ? Governorate::query()->where('country_id', $selectedCountry)->orderBy('name')->pluck('name','id')->toArray()
            : [];

        return view('admin.locations.states.create', [
            'countries' => $countries,
            'governorates' => $governorates,
            'selectedCountry' => $selectedCountry,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'country_id' => ['required','exists:countries,id'],
            'governorate_id' => ['required','exists:governorates,id'],
            'name' => ['required','string','max:190','unique:states,name,NULL,id,governorate_id,' . $request->governorate_id],
        ]);
        State::create([
            'governorate_id' => $data['governorate_id'],
            'name' => $data['name'],
        ]);
        return redirect()->route('admin.states.index')->with('status', __('messages.success_created'));
    }

    public function edit(State $state): View
    {
        $countries = Country::query()->orderBy('name')->pluck('name','id')->toArray();
        $selectedCountry = $state->governorate?->country_id;
        $governorates = $selectedCountry
            ? Governorate::query()->where('country_id', $selectedCountry)->orderBy('name')->pluck('name','id')->toArray()
            : [];

        return view('admin.locations.states.edit', [
            'state' => $state,
            'countries' => $countries,
            'governorates' => $governorates,
            'selectedCountry' => $selectedCountry,
        ]);
    }

    public function update(Request $request, State $state): RedirectResponse
    {
        $data = $request->validate([
            'country_id' => ['required','exists:countries,id'],
            'governorate_id' => ['required','exists:governorates,id'],
            'name' => ['required','string','max:190','unique:states,name,' . $state->id . ',id,governorate_id,' . $request->governorate_id],
        ]);

        $state->update([
            'governorate_id' => $data['governorate_id'],
            'name' => $data['name'],
        ]);

        return redirect()->route('admin.states.index')->with('status', __('messages.success_updated'));
    }

    public function destroy(State $state): RedirectResponse
    {
        $state->delete();
        return redirect()->route('admin.states.index')->with('status', __('messages.success_deleted'));
    }
}
