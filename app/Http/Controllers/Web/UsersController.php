<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
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
        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
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
        $roles = \Spatie\Permission\Models\Role::query()->pluck('name','name');
        return view('admin.users.edit', compact('user','roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
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
        $user->delete();
        return redirect()->route('admin.users.index')->with('status', __('messages.success_deleted'));
    }
}
