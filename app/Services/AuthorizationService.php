<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthorizationService
{
    public function createRole(string $name, array $permissions = []): Role
    {
        $role = Role::firstOrCreate(['name' => $name]);
        if (! empty($permissions)) {
            $this->setRolePermissions($role, $permissions);
        }
        return $role;
    }

    public function setRolePermissions(Role $role, array $permissions): void
    {
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
        $role->syncPermissions($permissions);
    }

    public function assignRoleToUser(User $user, string|array $roles): void
    {
        $user->syncRoles(Arr::wrap($roles));
    }

    public function givePermissionsToUser(User $user, string|array $permissions): void
    {
        foreach (Arr::wrap($permissions) as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
        $user->syncPermissions(Arr::wrap($permissions));
    }

    public function userHasPermission(User $user, string $permission): bool
    {
        return $user->can($permission);
    }
}

