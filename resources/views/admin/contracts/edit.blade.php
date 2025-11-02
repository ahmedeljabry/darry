@extends('admin.layouts.master')
@section('title', __('contracts.edit'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('contracts.title') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="" class="text-muted">{{ __('contracts.edit') }}</a></li>
    </ul>
@endsection

@section('content')
    <x-admin.form-card :title="__('contracts.edit')" :action="route('admin.contracts.update', $contract)" method="PUT" :back="route('admin.contracts.index')">
        <x-admin.input-solid name="contract_no" :label="__('contracts.contract_no')" :value="old('contract_no', $contract->contract_no)" />
        <x-admin.input-solid name="start_date" type="date" :label="__('contracts.start_date')" :value="old('start_date', optional($contract->start_date)->format('Y-m-d'))" required id="contract_start_date" />
        <x-admin.input-solid name="duration_months" type="number" min="1" max="365" :label="__('contracts.duration')" :value="old('duration_months', $contract->duration_months)" required id="duration_months" />
        <x-admin.input-solid name="end_date" type="date" :label="__('contracts.end_date')" :value="old('end_date', optional($contract->end_date)->format('Y-m-d'))" id="contract_end_date" readonly />
        <x-admin.select-solid name="property_id" id="contract_property_id" :label="__('contracts.property')" :options="$properties" :value="old('property_id', $contract->property_id)" required />
        <x-admin.select-solid name="unit_id" id="contract_unit_id" :label="__('contracts.unit')" :options="[]" required />
        <x-admin.select-solid name="tenant_id" :label="__('contracts.tenant')" :options="$tenants" :value="old('tenant_id', $contract->tenant_id)" required />
        <x-admin.select-solid name="payment_method" :label="__('contracts.method')" :options="['BANK_TRANSFER' => __('contract_payments.methods.bank_transfer'), 'CHECK' => __('contract_payments.methods.check'), 'CASH' => __('contract_payments.methods.cash')]" :value="old('payment_method', $contract->payment_method?->value)" />
        <x-admin.input-solid name="payment_day" type="number" min="1" max="31" :label="__('contracts.payment_day')" :value="old('payment_day', $contract->payment_day)" />
        <x-admin.input-solid name="rent_amount" type="number" step="0.01" min="0" :label="__('contracts.rent_amount')" id="rent_amount_input" :value="old('rent_amount', $contract->rent_amount)" />
    </x-admin.form-card>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const unitsData = @json($unitsPayload);

            const propertySelect = $('#contract_property_id');
            const unitSelect = $('#contract_unit_id');
            const rentInput = document.getElementById('rent_amount_input');
            const startInput = document.getElementById('contract_start_date');
            const durationInput = document.getElementById('duration_months');
            const endInput = document.getElementById('contract_end_date');

            const initialProperty = @js(old('property_id', $contract->property_id));
            const initialUnit = @js(old('unit_id', $contract->unit_id));

            function refreshUnits(propertyId, preselected = '') {
                const placeholder = unitSelect.data('placeholder') || '— اختر —';
                unitSelect.empty();
                unitSelect.append(new Option(placeholder, ''));

                unitsData.filter(unit => String(unit.property_id) === String(propertyId))
                    .forEach(unit => {
                        const option = new Option(unit.name, unit.id, false, false);
                        option.dataset.rent = unit.rent_amount;
                        unitSelect.append(option);
                    });

                if (preselected) {
                    unitSelect.val(preselected);
                } else {
                    unitSelect.val('');
                    if (rentInput) {
                        rentInput.value = '';
                    }
                }

                unitSelect.trigger('change');
            }

            function updateEndDate() {
                if (!startInput || !durationInput || !endInput) {
                    return;
                }
                const startVal = startInput.value;
                const durationVal = parseInt(durationInput.value, 10);
                if (!startVal || Number.isNaN(durationVal) || durationVal < 1) {
                    return;
                }
                const startDate = new Date(startVal + 'T00:00:00');
                const computed = new Date(startDate);
                computed.setMonth(computed.getMonth() + durationVal);
                computed.setDate(computed.getDate() - 1);
                const iso = computed.toISOString().slice(0, 10);
                endInput.value = iso;
            }

            propertySelect.on('change', function (event, selectedUnit = '') {
                refreshUnits(this.value, selectedUnit);
            });

            unitSelect.on('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                if (!selectedOption || !selectedOption.dataset.rent) {
                    return;
                }
                if (!rentInput.value || rentInput.value === '0') {
                    rentInput.value = selectedOption.dataset.rent;
                }
            });

            [startInput, durationInput].forEach(function (input) {
                if (input) {
                    input.addEventListener('change', updateEndDate);
                    input.addEventListener('keyup', updateEndDate);
                }
            });

            const defaultProperty = initialProperty || propertySelect.val();
            propertySelect.val(defaultProperty);
            propertySelect.trigger('change', [initialUnit]);
            propertySelect.trigger('change.select2');

            updateEndDate();
        });
    </script>
@endpush
