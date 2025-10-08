<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\DataTables\PermissionsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function index(PermissionsDataTable $dataTable)
    {
        return $dataTable->render('admin.permissions.index');
    }

    public function create(): View
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => 'required|string|max:255', 'guard_name' => 'nullable|string']);
        $data['guard_name'] = $data['guard_name'] ?? 'web';
        Permission::create($data);
        return redirect()->route('admin.permissions.index')->with('status', __('messages.success_created'));
    }

    public function edit(Permission $permission): View
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $data = $request->validate(['name' => 'required|string|max:255', 'guard_name' => 'nullable|string']);
        $data['guard_name'] = $data['guard_name'] ?? $permission->guard_name;
        $permission->update($data);
        return redirect()->route('admin.permissions.index')->with('status', __('messages.success_updated'));
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('status', __('messages.success_deleted'));
    }
}
