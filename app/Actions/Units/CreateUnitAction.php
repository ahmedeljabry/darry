<?php
declare(strict_types=1);

namespace App\Actions\Units;

use App\Models\Unit;
use Illuminate\Support\Facades\DB;

final class CreateUnitAction
{
    public function execute(array $data): Unit
    {
        return DB::transaction(fn () => Unit::create($data));
    }
}
