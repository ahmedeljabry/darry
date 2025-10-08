<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\DataTables\ExpensesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expenses\StoreExpenseRequest;
use App\Http\Requests\Expenses\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ExpensesController extends Controller
{
    public function index(ExpensesDataTable $dataTable)
    {
        return $dataTable->render('admin.expenses.index');
    }

    public function create(): View
    {
        $properties = Property::query()->pluck('name','id');
        $units = Unit::query()->select('id','name','property_id')->get();
        return view('admin.expenses.create', compact('properties','units'));
    }

    public function store(StoreExpenseRequest $request): RedirectResponse
    {
        Expense::create($request->validated());
        return redirect()->route('admin.expenses.index')->with('status', __('messages.success_created'));
    }

    public function edit(Expense $expense): View
    {
        $properties = Property::query()->pluck('name','id');
        $units = Unit::query()->select('id','name','property_id')->get();
        return view('admin.expenses.edit', compact('expense','properties','units'));
    }

    public function update(UpdateExpenseRequest $request, Expense $expense): RedirectResponse
    {
        $expense->update($request->validated());
        return redirect()->route('admin.expenses.index')->with('status', __('messages.success_updated'));
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();
        return redirect()->route('admin.expenses.index')->with('status', __('messages.success_deleted'));
    }
}

