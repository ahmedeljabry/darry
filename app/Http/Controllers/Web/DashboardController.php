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

        $totalIncome = (float) array_sum($incomeSeries);
        $totalExpenses = (float) array_sum($expenseSeries);
        $netResult = $totalIncome - $totalExpenses;

        $nearExpiringContractsList = Contract::query()
            ->with(['tenant:id,full_name', 'unit:id,name', 'property:id,name'])
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [$today, $nearDate])
            ->orderBy('end_date')
            ->limit(5)
            ->get(['id','contract_no','tenant_id','unit_id','property_id','rent_amount','end_date']);

        $recentPayments = ContractPayment::query()
            ->with(['tenant:id,full_name', 'property:id,name'])
            ->orderByDesc('paid_at')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get(['id','tenant_id','property_id','amount_paid','paid_at','method','details']);

        $recentExpenses = Expense::query()
            ->with(['property:id,name'])
            ->orderByDesc('date')
            ->limit(5)
            ->get(['id','property_id','title','amount','date','category']);

        $occupancyRate = $unitsCount > 0 ? round(($occupiedUnitsCount / $unitsCount) * 100) : 0;
        $vacancyRate = $unitsCount > 0 ? round(($vacantUnitsCount / $unitsCount) * 100) : 0;
        $maintenanceRate = $unitsCount > 0 ? round(($maintenanceUnitsCount / $unitsCount) * 100) : 0;

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
            'financeTotals' => [
                'income' => $totalIncome,
                'expenses' => $totalExpenses,
                'net' => $netResult,
            ],
            'nearExpiringContractsList' => $nearExpiringContractsList,
            'recentPayments' => $recentPayments,
            'recentExpenses' => $recentExpenses,
            'occupancyRate' => $occupancyRate,
            'vacancyRate' => $vacancyRate,
            'maintenanceRate' => $maintenanceRate,
        ]);
    }
}
