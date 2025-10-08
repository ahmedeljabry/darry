<?php
declare(strict_types=1);

namespace App\Services;

use App\Actions\Units\CreateUnitAction;
use App\Models\Unit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UnitsService
{
    public function __construct(private readonly CreateUnitAction $createUnit){}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Unit::query()->latest()->paginate($perPage);
    }

    public function get(): \Illuminate\Database\Eloquent\Collection
    {
        return Unit::all();
    }

    public function create(array $data): Unit
    {
        return $this->createUnit->execute($data);
    }

    public function update(Unit $unit, array $data): Unit
    {
        $unit->update($data);
        return $unit;
    }

    public function delete(Unit $unit): void
    {
        $unit->delete();
    }
}
