<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use App\Models\TenantRelative;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TenantsService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Tenant::query()->latest()->paginate($perPage);
    }

    public function create(array $data): Tenant
    {
        return DB::transaction(function() use ($data) {
            $tenantData = Arr::only($data, [
                'property_id','full_name','tenant_type','national_id_or_cr','work_or_study_place','email','phone','phone2','address',
            ]);
            $tenant = Tenant::create($tenantData);
            foreach (Arr::get($data, 'relatives', []) as $rel) {
                if ($this->isRelativeFilled($rel)) {
                    TenantRelative::create([
                        'tenant_id' => $tenant->id,
                        'name' => $rel['name'] ?? null,
                        'id_no' => $rel['id_no'] ?? null,
                        'phone' => $rel['phone'] ?? null,
                        'kinship' => $rel['kinship'] ?? null,
                    ]);
                }
            }
            return $tenant;
        });
    }

    public function update(Tenant $tenant, array $data): Tenant
    {
        return DB::transaction(function() use ($tenant, $data) {
            $tenantData = Arr::only($data, [
                'property_id','full_name','tenant_type','national_id_or_cr','work_or_study_place','email','phone','phone2','address',
            ]);
            $tenant->update($tenantData);
            $tenant->relatives()->delete();
            foreach (Arr::get($data, 'relatives', []) as $rel) {
                if ($this->isRelativeFilled($rel)) {
                    TenantRelative::create([
                        'tenant_id' => $tenant->id,
                        'name' => $rel['name'] ?? null,
                        'id_no' => $rel['id_no'] ?? null,
                        'phone' => $rel['phone'] ?? null,
                        'kinship' => $rel['kinship'] ?? null,
                    ]);
                }
            }
            return $tenant;
        });
    }

    private function isRelativeFilled(array $rel): bool
    {
        return (string)($rel['name'] ?? '') !== ''
            || (string)($rel['id_no'] ?? '') !== ''
            || (string)($rel['phone'] ?? '') !== ''
            || (string)($rel['kinship'] ?? '') !== '';
    }

    public function delete(Tenant $tenant): void
    {
        $tenant->delete();
    }
}
