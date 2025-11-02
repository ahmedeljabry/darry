<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'dashboard'   => ['view'],
            'units'       => ['view','create','update','delete','show'],
            'tenants'     => ['view','create','update','delete'],
            'leases'      => ['view','create','update','delete','show','renew','terminate','issue'],
            'invoices'    => ['view','show','delete'],
            'payments'    => ['view','create'],
            'properties'  => ['view','create','update','delete','show'],
            'facilities'  => ['view','create','update','delete','show'],
            'roles'       => ['view','create','update','delete'],
            'users'       => ['view','create','update','delete','show'],
            'settings'    => ['view','update'],
            'permissions' => ['view','create','update','delete'],
            'owners'      => ['view','create','update','delete','show'],
            'expenses'    => ['view','create','update','delete','show'],
            'contracts'   => ['view','create','update','delete','show'],
            'contract_payments' => ['view','create','update','delete','show'],
            'countries'   => ['view','create','update','delete'],
            'governorates'=> ['view','create','update','delete'],
            'states'      => ['view','create','update','delete'],
        ];

        $all = [];
        foreach ($map as $group => $actions) {
            foreach ($actions as $action) {
                $all[] = "$group.$action";
            }
        }

        foreach ($all as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::pluck('name')->all());

        if ($admin = User::where('email', 'admin@example.com')->first()) {
            $admin->syncRoles(['admin']);
        }
    }
}
