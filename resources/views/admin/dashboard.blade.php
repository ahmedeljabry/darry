@extends('admin.layouts.master')

@section('title', __('messages.dashboard'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('messages.dashboard') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <span class="text-muted">{{ __('messages.dashboard') }}</span>
        </li>
    </ul>
@endsection

@section('content')
    @php
        $user = auth()->user();
        $currency = $currency ?? 'SAR';
        $financeTotals = $financeTotals ?? ['income' => 0, 'expenses' => 0, 'net' => 0];
        $propertySnapshot = $propertySnapshot ?? null;
        $kpiCards = [
            [
                'title' => 'إجمالي الوحدات',
                'value' => number_format($kpiUnits ?? 0),
                'description' => 'جميع الوحدات المسجلة',
                'icon' => 'la la-building',
                'color' => 'primary',
                'link' => route('admin.units.index'),
            ],
            [
                'title' => 'الوحدات المشغولة',
                'value' => number_format($kpiOccupiedUnits ?? 0),
                'description' => 'وحدات تحت التعاقد حالياً',
                'icon' => 'la la-user-check',
                'color' => 'success',
                'link' => route('admin.units.index'),
            ],
            [
                'title' => 'الوحدات الشاغرة',
                'value' => number_format($kpiVacantUnits ?? 0),
                'description' => 'فرص تأجير متاحة',
                'icon' => 'la la-door-open',
                'color' => 'info',
                'link' => route('admin.units.index'),
            ],
            [
                'title' => 'عقود تقترب من الانتهاء',
                'value' => number_format($kpiContractsNearExpiry ?? 0),
                'description' => 'خلال الثلاثين يوماً القادمة',
                'icon' => 'la la-hourglass-half',
                'color' => 'warning',
                'link' => route('admin.contracts.index'),
            ],
        ];
    @endphp

    <!-- Hero -->
    <div class="card card-custom mb-10 bgi-no-repeat bgi-position-y-center bgi-size-cover"
         style="background-image: url({{ asset('admin/assets/media/svg/patterns/wave.svg') }});">
        <div class="card-body p-9">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
                <div>
                    <span class="badge badge-light-primary font-weight-bold px-4 py-2 mb-4">
                        {{ now()->translatedFormat('l d F Y') }}
                    </span>
                    <h2 class="text-dark font-weight-bolder mb-3">
                        أهلاً {{ $user?->name ?? '' }}, لوحة القيادة جاهزة للعرض
                    </h2>
                    <p class="text-muted font-size-lg mb-0">
                        راجع مؤشراتك المالية والتشغيلية بسرعة، وتصرف مبكراً تجاه العقود والمدفوعات القريبة.
                    </p>
                </div>
                <div class="mt-6 mt-md-0 text-right">
                    <div class="d-flex align-items-center justify-content-end mb-4">
                        <span class="text-muted mr-3">صافي التدفق آخر 12 شهر</span>
                        <span class="badge badge-{{ $financeTotals['net'] >= 0 ? 'success' : 'danger' }} font-weight-bold px-4 py-3">
                            {{ number_format($financeTotals['net'], 2) }} {{ $currency }}
                        </span>
                    </div>
                    <a href="{{ route('admin.contracts.index') }}" class="btn btn-light-primary btn-sm px-6">
                        <i class="la la-file-invoice-dollar ml-2"></i>إدارة العقود
                    </a>
                    <a href="{{ route('admin.expenses.index') }}" class="btn btn-outline-light btn-sm px-6 ml-2">
                        <i class="la la-receipt ml-2"></i>سجل المصروفات
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if ($propertySnapshot)
        <div class="card card-custom mb-8 border border-primary">
            <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
                <div class="mb-6 mb-lg-0">
                    <span class="badge badge-light-primary font-weight-bold px-4 py-2 mb-3">
                        {{ __('messages.property_snapshot.title') }}
                    </span>
                    <h3 class="text-dark font-weight-bolder mb-2">{{ $propertySnapshot['name'] }}</h3>
                    <p class="text-muted mb-0">{{ __('messages.property_snapshot.subtitle') }}</p>
                    <div class="text-muted font-size-sm d-flex align-items-center mt-4">
                        <i class="la la-map-marker-alt text-primary font-size-h4 ml-2"></i>
                        <span>{{ $propertySnapshot['address'] ?: __('messages.not_available') }}</span>
                    </div>
                </div>
                <div class="d-flex flex-wrap text-left text-lg-right">
                    <div class="px-lg-5 mb-4 mb-lg-0">
                        <span class="text-muted font-size-sm">{{ __('messages.property_snapshot.use_type') }}</span>
                        <div class="text-dark font-weight-bolder font-size-h4">
                            @if ($propertySnapshot['use_type'])
                                {{ __('properties.use_types.' . $propertySnapshot['use_type']) }}
                            @else
                                {{ __('messages.not_available') }}
                            @endif
                        </div>
                    </div>
                    <div class="px-lg-5 mb-4 mb-lg-0">
                        <span class="text-muted font-size-sm">{{ __('messages.property_snapshot.area') }}</span>
                        <div class="text-dark font-weight-bolder font-size-h4">
                            {{ $propertySnapshot['area'] ? number_format($propertySnapshot['area']) : '—' }}
                        </div>
                    </div>
                    <div class="px-lg-5 mb-4 mb-lg-0">
                        <span class="text-muted font-size-sm">{{ __('messages.property_snapshot.floors') }}</span>
                        <div class="text-dark font-weight-bolder font-size-h4">
                            {{ number_format($propertySnapshot['floors']) }}
                        </div>
                    </div>
                    <div class="px-lg-5">
                        <span class="text-muted font-size-sm">{{ __('messages.property_snapshot.units_total') }}</span>
                        <div class="text-dark font-weight-bolder font-size-h4">
                            {{ number_format($propertySnapshot['units']['total']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-12">
            <div class="col-xl-4 mb-8">
                <div class="card card-custom shadow-sm h-100">
                    <div class="card-body">
                        <h4 class="text-dark font-weight-bolder mb-4">{{ __('messages.property_snapshot.units_overview') }}</h4>
                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <span class="text-muted">{{ __('messages.property_snapshot.occupied') }}</span>
                            <span class="badge badge-light-success font-weight-bold px-4 py-2">
                                {{ $propertySnapshot['units']['occupancy_rate'] }}%
                            </span>
                        </div>
                        <div class="progress progress-md">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: {{ $propertySnapshot['units']['occupancy_rate'] }}%;"
                                 aria-valuenow="{{ $propertySnapshot['units']['occupancy_rate'] }}" aria-valuemin="0"
                                 aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-6">
                            <div>
                                <span class="text-muted font-size-sm">{{ __('messages.property_snapshot.occupied') }}</span>
                                <div class="text-dark font-weight-bolder font-size-h4">
                                    {{ number_format($propertySnapshot['units']['occupied']) }}
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-muted font-size-sm">{{ __('messages.property_snapshot.vacant') }}</span>
                                <div class="text-dark font-weight-bolder font-size-h4">
                                    {{ number_format($propertySnapshot['units']['vacant']) }}
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-5"></div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted font-size-sm">{{ __('messages.property_snapshot.maintenance') }}</span>
                            <span class="text-dark font-weight-bolder">
                                {{ number_format($propertySnapshot['units']['maintenance']) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 mb-8">
                <div class="card card-custom shadow-sm h-100">
                    <div class="card-body">
                        <h4 class="text-dark font-weight-bolder mb-4">{{ __('messages.property_snapshot.financial') }}</h4>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted">{{ __('messages.property_snapshot.income') }}</span>
                            <span class="text-dark font-weight-bolder">
                                {{ number_format($propertySnapshot['financial']['income'], 2) }} {{ $currency }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted">{{ __('messages.property_snapshot.expenses') }}</span>
                            <span class="text-dark font-weight-bolder">
                                {{ number_format($propertySnapshot['financial']['expenses'], 2) }} {{ $currency }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <span class="text-muted">{{ __('messages.property_snapshot.net') }}</span>
                            <span class="text-{{ $propertySnapshot['financial']['net'] >= 0 ? 'success' : 'danger' }} font-weight-bolder">
                                {{ number_format($propertySnapshot['financial']['net'], 2) }} {{ $currency }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="la la-calendar text-muted font-size-lg ml-2"></i>
                            <span class="text-muted font-size-sm">
                                {{ __('messages.property_snapshot.date_range') }}:
                                {{ $propertySnapshot['financial']['from'] }} - {{ $propertySnapshot['financial']['to'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 mb-8">
                <div class="card card-custom shadow-sm h-100">
                    <div class="card-body">
                        <h4 class="text-dark font-weight-bolder mb-4">{{ __('messages.property_snapshot.contracts') }}</h4>
                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <div>
                                <span class="text-muted font-size-sm">{{ __('messages.property_snapshot.active_contracts') }}</span>
                                <div class="text-dark font-weight-bolder font-size-h3">
                                    {{ number_format($propertySnapshot['contracts']['active']) }}
                                </div>
                            </div>
                            <a href="{{ route('admin.contracts.index') }}"
                               class="btn btn-sm btn-light-primary font-weight-bold">
                                {{ __('menu.contracts') }}
                            </a>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted">{{ __('messages.property_snapshot.avg_rent') }}</span>
                            <span class="text-dark font-weight-bolder">
                                {{ number_format($propertySnapshot['contracts']['avg_rent'], 2) }} {{ $currency }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">{{ __('messages.property_snapshot.expiring') }}</span>
                            <span class="badge badge-light-warning font-weight-bold px-4 py-2">
                                {{ number_format($propertySnapshot['contracts']['expiring']) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- KPI Cards -->
    <div class="row g-6 g-xl-9">
        @foreach($kpiCards as $card)
            <div class="col-sm-6 col-xl-3">
                <div class="card card-custom shadow-sm hover-elevate-up h-100">
                    <div class="card-body d-flex">
                        <div class="mr-4">
                            <span class="symbol symbol-50 symbol-light-{{ $card['color'] }}">
                                <span class="symbol-label">
                                    <i class="{{ $card['icon'] }} text-{{ $card['color'] }} font-size-h3"></i>
                                </span>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-dark font-weight-bolder font-size-h4 mb-1">{{ $card['value'] }}</span>
                            <span class="text-muted font-weight-bold">{{ $card['title'] }}</span>
                            <span class="text-muted font-size-sm mt-2">{{ $card['description'] }}</span>
                            <a href="{{ $card['link'] }}" class="text-{{ $card['color'] }} font-weight-bolder font-size-sm mt-4">
                                التفاصيل
                                <i class="la la-angle-left mr-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Charts & Occupancy -->
    <div class="row g-6 g-xl-9 mt-1">
        <div class="col-xl-8">
            <div class="card card-custom shadow-sm h-100">
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">تدفق الإيرادات والمصروفات</span>
                        <span class="text-muted mt-3 font-size-sm">آخر 12 شهرًا</span>
                    </h3>
                    <div class="card-toolbar">
                        <div class="d-flex flex-column text-right">
                            <span class="text-muted font-weight-bold">إجمالي الدخل</span>
                            <span class="text-dark font-weight-bolder">{{ number_format($financeTotals['income'], 2) }} {{ $currency }}</span>
                        </div>
                        <div class="d-flex flex-column text-right ml-6">
                            <span class="text-muted font-weight-bold">إجمالي المصروف</span>
                            <span class="text-dark font-weight-bolder">{{ number_format($financeTotals['expenses'], 2) }} {{ $currency }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="dashboard_finance_chart" style="min-height: 340px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card card-custom shadow-sm h-100">
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">نسبة الإشغال الحالية</span>
                        <span class="text-muted mt-3 font-size-sm">تحليل سريع لحالة الوحدات</span>
                    </h3>
                </div>
                <div class="card-body pt-0">
                    <div id="dashboard_occupancy_chart" style="min-height: 280px;"></div>
                    <div class="mt-8">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-muted font-weight-bold">الوحدات المشغولة</span>
                            <span class="font-weight-bolder text-dark">{{ $occupancyRate }}%</span>
                        </div>
                        <div class="progress progress-xs mb-5">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $occupancyRate }}%;"></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-muted font-weight-bold">الوحدات الشاغرة</span>
                            <span class="font-weight-bolder text-dark">{{ $vacancyRate }}%</span>
                        </div>
                        <div class="progress progress-xs mb-5">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $vacancyRate }}%;"></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-muted font-weight-bold">وحدات تحت الصيانة</span>
                            <span class="font-weight-bolder text-dark">{{ $maintenanceRate }}%</span>
                        </div>
                        <div class="progress progress-xs">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $maintenanceRate }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lists -->
    <div class="row g-6 g-xl-9 mt-1">
        <div class="col-xl-4">
            <div class="card card-custom shadow-sm h-100">
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">أقرب العقود انتهاءً</span>
                        <span class="text-muted mt-3 font-size-sm">تابع فترات التجديد القادمة</span>
                    </h3>
                    <div class="card-toolbar">
                        <a href="{{ route('admin.contracts.index') }}" class="btn btn-sm btn-light-primary font-weight-bolder">
                            عرض الكل
                        </a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @forelse($nearExpiringContractsList as $contract)
                        <div class="d-flex align-items-center py-3 border-bottom border-light">
                            <div class="symbol symbol-45 symbol-light-warning">
                                <span class="symbol-label">
                                    <i class="la la-file-contract text-warning font-size-h4"></i>
                                </span>
                            </div>
                            <div class="ml-4 flex-grow-1">
                                <a href="{{ route('admin.contracts.edit', $contract->id) }}" class="text-dark font-weight-bolder font-size-lg">
                                    {{ $contract->contract_no ?? 'بدون رقم' }}
                                </a>
                                <div class="text-muted font-size-sm mt-1">
                                    {{ $contract->tenant?->full_name ?? 'مستأجر غير محدد' }} • {{ $contract->unit?->name ?? 'وحدة غير محددة' }}
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="badge badge-light-danger font-weight-bold mb-2">
                                    {{ optional($contract->end_date)->format('d M Y') }}
                                </span>
                                <div class="text-muted font-size-sm">
                                    {{ number_format($contract->rent_amount ?? 0, 2) }} {{ $currency }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-muted">
                            لا توجد عقود قريبة الانتهاء.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card card-custom shadow-sm h-100">
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">آخر المدفوعات المستلمة</span>
                        <span class="text-muted mt-3 font-size-sm">إجمالي المدفوعات المكتملة مؤخراً</span>
                    </h3>
                </div>
                <div class="card-body pt-0">
                    @forelse($recentPayments as $payment)
                        <div class="d-flex align-items-center py-3 border-bottom border-light">
                            <div class="symbol symbol-45 symbol-light-success">
                                <span class="symbol-label">
                                    <i class="la la-credit-card text-success font-size-h4"></i>
                                </span>
                            </div>
                            <div class="ml-4 flex-grow-1">
                                <span class="text-dark font-weight-bolder">
                                    {{ number_format($payment->amount_paid, 2) }} {{ $currency }}
                                </span>
                                <div class="text-muted font-size-sm mt-1">
                                    {{ $payment->tenant?->full_name ?? 'مستأجر' }} • {{ $payment->property?->name ?? 'عقار غير محدد' }}
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="badge badge-light-success font-weight-bold">
                                    {{ optional($payment->paid_at)->format('d M Y') ?? '--' }}
                                </span>
                                @php
                                    $methodKey = strtolower($payment->method?->value ?? (is_string($payment->method) ? $payment->method : 'cash'));
                                @endphp
                                <div class="text-muted font-size-sm mt-1">
                                    {{ __('contract_payments.methods.' . $methodKey) }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-muted">
                            لا توجد مدفوعات مسجلة مؤخراً.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card card-custom shadow-sm h-100">
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">آخر المصروفات المسجلة</span>
                        <span class="text-muted mt-3 font-size-sm">راقب الصرف التشغيلي أولاً بأول</span>
                    </h3>
                </div>
                <div class="card-body pt-0">
                    @forelse($recentExpenses as $expense)
                        <div class="d-flex align-items-center py-3 border-bottom border-light">
                            <div class="symbol symbol-45 symbol-light-danger">
                                <span class="symbol-label">
                                    <i class="la la-file-invoice-dollar text-danger font-size-h4"></i>
                                </span>
                            </div>
                            <div class="ml-4 flex-grow-1">
                                <span class="text-dark font-weight-bolder">
                                    {{ $expense->title ?? 'مصروف' }}
                                </span>
                                <div class="text-muted font-size-sm mt-1">
                                    {{ $expense->property?->name ?? 'عقار غير محدد' }} • {{ $expense->category ?? 'غير مصنف' }}
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="badge badge-light-danger font-weight-bold">
                                    {{ number_format($expense->amount, 2) }} {{ $currency }}
                                </span>
                                <div class="text-muted font-size-sm mt-1">
                                    {{ optional($expense->date)->format('d M Y') ?? '--' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-muted">
                            لا توجد مصروفات حديثة.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admin/assets/js/pages/features/charts/apexcharts.js') }}"></script>
    <script>
        (function () {
            if (typeof ApexCharts === 'undefined') {
                return;
            }

            const months = @json($chart['months'] ?? []);
            const income = @json($chart['income'] ?? []);
            const expenses = @json($chart['expenses'] ?? []);
            const currency = @json($currency ?? 'SAR');

            const net = income.map(function (value, index) {
                const expense = (typeof expenses[index] !== 'undefined') ? expenses[index] : 0;
                return Number((Number(value) - Number(expense)).toFixed(2));
            });

            const financeChart = new ApexCharts(document.querySelector('#dashboard_finance_chart'), {
                series: [
                    {name: 'الدخل', type: 'column', data: income},
                    {name: 'المصروفات', type: 'column', data: expenses},
                    {name: 'الصافي', type: 'line', data: net}
                ],
                chart: {type: 'line', height: 360, stacked: false, toolbar: {show: false}},
                dataLabels: {enabled: false},
                stroke: {width: [1, 1, 3]},
                xaxis: {categories: months},
                yaxis: [{
                    title: {text: currency},
                    labels: {
                        formatter: function (val) {
                            return Number(val).toLocaleString(undefined, {maximumFractionDigits: 0});
                        }
                    }
                }],
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function (val) {
                            return Number(val).toLocaleString(undefined, {maximumFractionDigits: 2}) + ' ' + currency;
                        }
                    }
                },
                legend: {position: 'top'},
                colors: ['#3699FF', '#F64E60', '#1BC5BD'],
                grid: {strokeDashArray: 4}
            });
            financeChart.render();

            const occLabels = @json($chart['occupancy_labels'] ?? []);
            const occData = @json($chart['occupancy_data'] ?? []);

            const occupancyChart = new ApexCharts(document.querySelector('#dashboard_occupancy_chart'), {
                series: occData,
                chart: {type: 'donut', width: '100%', height: 300},
                labels: occLabels,
                legend: {position: 'bottom'},
                dataLabels: {
                    formatter: function (val, opts) {
                        const value = opts.w.config.series[opts.seriesIndex] || 0;
                        return value.toLocaleString();
                    }
                },
                colors: ['#1BC5BD', '#3699FF', '#FFA800'],
                stroke: {colors: ['#ffffff']},
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                name: {show: true, fontSize: '14px', fontWeight: 600},
                                value: {show: true, fontSize: '16px', fontWeight: 700, formatter: function (val) {return Number(val).toLocaleString();}},
                                total: {
                                    show: true,
                                    label: 'إجمالي الوحدات',
                                    formatter: function () {
                                        return occData.reduce((a, b) => a + b, 0).toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                }
            });
            occupancyChart.render();
        })();
    </script>
@endpush
