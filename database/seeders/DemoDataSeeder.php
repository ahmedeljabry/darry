<?php

namespace Database\Seeders;

use App\Domain\Enums\PaymentMethod;
use App\Domain\Enums\PropertyUseType;
use App\Domain\Enums\RentType;
use App\Domain\Enums\UnitOccupancyStatus;
use App\Domain\Enums\UnitStatus;
use App\Domain\Enums\UnitType;
use App\Models\Contract;
use App\Models\ContractPayment;
use App\Models\Expense;
use App\Models\Property;
use App\Models\Setting;
use App\Models\Tenant;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Basic settings
        Setting::set('currency', 'SAR');

        // Create a demo property
        $property = Property::firstOrCreate(
            ['name' => 'مجمع النخيل السكني'],
            [
                'country' => 'السعودية',
                'city' => 'الرياض',
                'use_type' => PropertyUseType::RESIDENTIAL->value,
                'area_sqm' => 15000,
            ]
        );

        // Create tenants
        $tenants = collect();
        for ($i = 1; $i <= 18; $i++) {
            $tenants->push(Tenant::updateOrCreate(
                ['email' => "tenant{$i}@example.com"],
                [
                    'property_id' => $property->id,
                    'full_name' => "مستأجر {$i}",
                    'phone' => '05' . rand(10000000, 99999999),
                ]
            ));
        }

        // Create units with mixed occupancy
        $units = collect();
        $occupancyPool = [
            UnitOccupancyStatus::OCCUPIED,
            UnitOccupancyStatus::VACANT,
            UnitOccupancyStatus::VACANT,
            UnitOccupancyStatus::MAINTENANCE,
        ];
        for ($i = 1; $i <= 30; $i++) {
            $rent = rand(1500, 5500);
            $units->push(Unit::create([
                'property_id' => $property->id,
                'name' => 'وحدة ' . str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                'unit_type' => [UnitType::APARTMENT->value, UnitType::ROOM->value][rand(0, 1)],
                'capacity' => rand(1, 6),
                'rooms' => rand(1, 5),
                'toilets' => rand(1, 3),
                'category' => null,
                'rent_type' => RentType::MONTHLY->value,
                'rent_amount' => $rent,
                'electricity_acc' => 'ELEC' . rand(10000, 99999),
                'water_acc' => 'WATR' . rand(10000, 99999),
                'status' => UnitStatus::ACTIVE->value,
                'occupancy_status' => $occupancyPool[array_rand($occupancyPool)]->value,
            ]));
        }

        $today = Carbon::today();
        $occupiedUnits = $units->filter(fn($u) => $u->occupancy_status === UnitOccupancyStatus::OCCUPIED->value)->values();
        foreach ($occupiedUnits as $idx => $unit) {
            $tenant = $tenants[$idx % $tenants->count()];
            $start = $today->copy()->subMonths(rand(1, 12))->startOfMonth();
            $durationMonths = 12;
            $end = $start->copy()->addMonths($durationMonths)->subDay();
            $paymentDay = rand(1, 28);
            $contract = Contract::create([
                'contract_no' => 'CNT-' . strtoupper(Str::random(6)) . '-' . $unit->id,
                'property_id' => $property->id,
                'unit_id' => $unit->id,
                'tenant_id' => $tenant->id,
                'start_date' => $start,
                'duration_months' => $durationMonths,
                'end_date' => $end,
                'payment_method' => [PaymentMethod::CASH->value, PaymentMethod::BANK_TRANSFER->value][rand(0, 1)],
                'payment_day' => $paymentDay,
                'rent_amount' => $unit->rent_amount,
            ]);

            $cursor = $start->copy();
            while ($cursor->lte(min($today, $end))) {
                $due = $cursor->copy()->day($paymentDay);
                $paid = rand(0, 100) < 82;
                ContractPayment::create([
                    'property_id' => $property->id,
                    'unit_id' => $unit->id,
                    'tenant_id' => $tenant->id,
                    'period_month' => (int) $due->format('n'),
                    'period_year' => (int) $due->format('Y'),
                    'amount_due' => $unit->rent_amount,
                    'amount_paid' => $paid ? $unit->rent_amount : 0,
                    'due_date' => $due->copy()->toDateString(),
                    'paid_at' => $paid ? $due->copy()->addDays(rand(0, 5)) : null,
                    'method' => $paid ? PaymentMethod::CASH->value : null,
                    'details' => $paid ? 'دفعة إيجار شهرية' : 'غير مسدد',
                ]);
                $cursor->addMonth();
            }
        }

        $categories = ['صيانة', 'نظافة', 'مياه', 'كهرباء', 'رسوم حكومية'];
        for ($m = 11; $m >= 0; $m--) {
            $month = $today->copy()->subMonths($m);
            $count = rand(2, 5);
            for ($i = 0; $i < $count; $i++) {
                Expense::create([
                    'property_id' => $property->id,
                    'unit_id' => rand(0, 1) ? $units->random()->id : null,
                    'title' => $categories[array_rand($categories)],
                    'amount' => rand(150, 2500),
                    'date' => $month->copy()->day(rand(1, 28))->toDateString(),
                    'receipt_no' => 'EXP-' . strtoupper(Str::random(5)),
                    'category' => $categories[array_rand($categories)],
                ]);
            }
        }
    }
}
