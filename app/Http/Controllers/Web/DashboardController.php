<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Contract;
use App\Models\Unit;
use Carbon\Carbon;
use App\Domain\Enums\UnitOccupancyStatus;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $unitsCount = Unit::count();
        $vacantUnitsCount = Unit::query()->where('occupancy_status', UnitOccupancyStatus::VACANT)->count();

        $today = Carbon::today();
        $nearDate = $today->copy()->addDays(30);
        $nearExpiringContracts = Contract::query()
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [$today, $nearDate])
            ->count();

        $months = ['ينا','فبر','مار','أبر','ماي','يون','يول','أغس','سبت','أكت','نوف','ديس'];
  

        return view('admin.dashboard', [
            'kpiUnits' => $unitsCount,
            'kpiVacantUnits' => $vacantUnitsCount,
            'kpiContractsNearExpiry' => $nearExpiringContracts,
            'months' => $months,
        ]);
    }
}
