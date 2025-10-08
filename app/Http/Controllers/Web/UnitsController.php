<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Units\{ StoreUnitRequest , UpdateUnitRequest };
use App\Models\Unit;
use App\Services\UnitsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\DataTables\UnitsDataTable;

class UnitsController extends Controller
{
    public function __construct(private readonly UnitsService $units){}

    public function index(UnitsDataTable $dataTable)
    {
         return $dataTable->render('admin.units.index');
    }

    public function create(): View
    {
        return view('admin.units.create');
    }

    public function store(StoreUnitRequest $request): RedirectResponse
    {
        $this->units->create($request->validated());
        return redirect()->route('admin.units.index')->with('status', __('messages.success_created'));
    }

    public function show(Unit $unit): View
    {
        return view('admin.units.show', compact('unit'));
    }

    public function edit(Unit $unit): View
    {
        return view('admin.units.edit', compact('unit'));
    }

    public function update(UpdateUnitRequest $request, Unit $unit): RedirectResponse
    {
        $this->units->update($unit, $request->validated());
        return redirect()->route('admin.units.index')->with('status', __('messages.success_updated'));
    }

    public function destroy(Unit $unit): RedirectResponse
    {
        $this->units->delete($unit);
        return redirect()->route('admin.units.index')->with('status', __('messages.success_deleted'));
    }
}

