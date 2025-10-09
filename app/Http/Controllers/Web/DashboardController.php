<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ContractPayment;
use App\Models\Expense;
use App\Models\Contract;
use App\Models\Unit;
use Carbon\Carbon;
use App\Domain\Enums\UnitOccupancyStatus;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $unitsCount = Unit::count();
        $vacantUnitsCount = Unit::query()->where('occupancy_status', UnitOccupancyStatus::VACANT)->count();
        $occupiedUnitsCount = Unit::query()->where('occupancy_status', UnitOccupancyStatus::OCCUPIED)->count();
        $maintenanceUnitsCount = Unit::query()->where('occupancy_status', UnitOccupancyStatus::MAINTENANCE)->count();

        $today = Carbon::today();
        $nearDate = $today->copy()->addDays(30);
        $nearExpiringContracts = Contract::query()
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [$today, $nearDate])
            ->count();

        $monthsAr = ['ينا','فبر','مار','أبر','ماي','يون','يول','أغس','سبت','أكت','نوف','ديس'];
        $chartMonths = [];
        $incomeSeries = [];
        $expenseSeries = [];
        for ($i = 11; $i >= 0; $i--) {
            $d = $today->copy()->subMonths($i);
            $chartMonths[] = $monthsAr[$d->format('n') - 1] . ' ' . $d->format('y');
            $start = $d->copy()->startOfMonth();
            $end = $d->copy()->endOfMonth();
            $incomeSeries[] = (float) ContractPayment::query()
                ->whereNotNull('paid_at')
                ->whereBetween('paid_at', [$start, $end])
                ->sum('amount_paid');
            $expenseSeries[] = (float) Expense::query()
                ->whereBetween('date', [$start, $end])
                ->sum('amount');
        }

        $occLabels = ['مشغولة', 'شاغرة', 'صيانة'];
        $occData = [
            (int) $occupiedUnitsCount,
            (int) $vacantUnitsCount,
            (int) $maintenanceUnitsCount,
        ];


        return view('admin.dashboard', [
            'kpiUnits' => $unitsCount,
            'kpiOccupiedUnits' => $occupiedUnitsCount,
            'kpiVacantUnits' => $vacantUnitsCount,
            'kpiContractsNearExpiry' => $nearExpiringContracts,
            'currency' => (string) Setting::get('currency', 'SAR'),
            'chart' => [
                'months' => $chartMonths,
                'income' => $incomeSeries,
                'expenses' => $expenseSeries,
                'occupancy_labels' => $occLabels,
                'occupancy_data' => $occData,
            ],
        ]);
    }
}
