<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\DataTables\RolesDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles.view')->only(['index']);
        $this->middleware('permission:roles.create')->only(['create','store']);
        $this->middleware('permission:roles.update')->only(['edit','update']);
        $this->middleware('permission:roles.delete')->only(['destroy']);
    }
    public function index(RolesDataTable $dataTable)
    {
        return $dataTable->render('admin.roles.index');
    }

    public function create(): View
    {
        $permissions = Permission::query()->orderBy('name')->get();
        $permissionGroups = $permissions->groupBy(function ($perm) {
            $group = Str::before($perm->name, '.');
            return $group !== '' ? $group : 'general';
        });
        return view('admin.roles.create', compact('permissions', 'permissionGroups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'string'
        ]);
        $role = Role::create(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);
        return redirect()->route('admin.roles.index')->with('status', __('messages.success_created'));
    }

    public function edit(Role $role): View
    {
        $permissions = Permission::query()->orderBy('name')->get();
        $permissionGroups = $permissions->groupBy(function ($perm) {
            $group = Str::before($perm->name, '.');
            return $group !== '' ? $group : 'general';
        });
        $rolePermissions = $role->permissions->pluck('name')->all();
        return view('admin.roles.edit', compact('role','permissions','permissionGroups','rolePermissions'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'string'
        ]);
        $role->update(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);
        return redirect()->route('admin.roles.index')->with('status', __('messages.success_updated'));
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();
        return redirect()->route('admin.roles.index')->with('status', __('messages.success_deleted'));
    }
}
