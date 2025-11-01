<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('admin.users.index');
    }

    public function create(): View
    {
        $roles = \Spatie\Permission\Models\Role::query()->pluck('name','name');
        $propertiesCollection = Property::query()->pluck('name', 'id');
        $currentPropertyId = Auth::user()?->property_id;
        $canManageSystem = $currentPropertyId === null;
        $properties = $canManageSystem
            ? $propertiesCollection->toArray()
            : $propertiesCollection->only([$currentPropertyId])->toArray();
        if ($canManageSystem) {
            $properties = ['' => __('users.scope_system')] + $properties;
        }

        return view('admin.users.create', [
            'roles' => $roles,
            'properties' => $properties,
            'canManageSystem' => $canManageSystem,
            'currentPropertyId' => $currentPropertyId,
            'currentPropertyName' => $currentPropertyId ? $propertiesCollection[$currentPropertyId] ?? null : null,
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $currentPropertyId = Auth::user()?->property_id;
        $data['property_id'] = $currentPropertyId ?? ($data['property_id'] ?? null);
        if ($data['property_id'] === '') {
            $data['property_id'] = null;
        }
        $data['password'] = Hash::make($data['password']);
        $roleName = $data['role'] ?? null;
        unset($data['role']);
        $user = User::create($data);
        if ($roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $user->syncRoles([$roleName]);
        }
        return redirect()->route('admin.users.index')->with('status', __('messages.success_created'));
    }

    public function edit(User $user): View
    {
        $this->guardPropertyAccess($user);
        $roles = \Spatie\Permission\Models\Role::query()->pluck('name','name');
        $propertiesCollection = Property::query()->pluck('name', 'id');
        $currentPropertyId = Auth::user()?->property_id;
        $canManageSystem = $currentPropertyId === null;
        $properties = $canManageSystem
            ? $propertiesCollection->toArray()
            : $propertiesCollection->only([$currentPropertyId])->toArray();
        if ($canManageSystem) {
            $properties = ['' => __('users.scope_system')] + $properties;
        }

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles,
            'properties' => $properties,
            'canManageSystem' => $canManageSystem,
            'currentPropertyId' => $currentPropertyId,
            'currentPropertyName' => $currentPropertyId ? $propertiesCollection[$currentPropertyId] ?? null : null,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->guardPropertyAccess($user);
        $data = $request->validated();
        $currentPropertyId = Auth::user()?->property_id;
        $data['property_id'] = $currentPropertyId ?? ($data['property_id'] ?? null);
        if ($data['property_id'] === '') {
            $data['property_id'] = null;
        }
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $roleName = $data['role'] ?? null;
        unset($data['role']);
        $user->update($data);
        if ($roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $user->syncRoles([$roleName]);
        }
        return redirect()->route('admin.users.index')->with('status', __('messages.success_updated'));
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->guardPropertyAccess($user);
        $user->delete();
        return redirect()->route('admin.users.index')->with('status', __('messages.success_deleted'));
    }

    private function guardPropertyAccess(User $user): void
    {
        $currentPropertyId = Auth::user()?->property_id;
        if ($currentPropertyId && $user->property_id !== $currentPropertyId) {
            abort(403);
        }
    }
}
