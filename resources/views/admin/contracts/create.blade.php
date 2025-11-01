@extends('admin.layouts.master')
@section('title', __('contracts.create'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('contracts.title') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="" class="text-muted">{{ __('contracts.create') }}</a></li>
    </ul>
@endsection

@section('content')
    <x-admin.form-card :title="__('contracts.create')" :action="route('admin.contracts.store')" :back="route('admin.contracts.index')">
        <x-admin.input-solid name="contract_no" :label="__('contracts.contract_no')" :placeholder="__('contracts.auto_generate')" />
        <x-admin.input-solid name="start_date" type="date" :label="__('contracts.start_date')" required id="contract_start_date" />
        <x-admin.input-solid name="duration_months" type="number" min="1" max="365" :label="__('contracts.duration')" required id="duration_months" />
        <x-admin.input-solid name="end_date" type="date" :label="__('contracts.end_date')" id="contract_end_date" readonly />
        <x-admin.select-solid name="property_id" id="contract_property_id" :label="__('contracts.property')" :options="$properties" required />
        <x-admin.select-solid name="unit_id" id="contract_unit_id" :label="__('contracts.unit')" :options="[]" required />
        <x-admin.select-solid name="tenant_id" :label="__('contracts.tenant')" :options="$tenants" required />
        <x-admin.select-solid name="payment_method" :label="__('contracts.method')" :options="['BANK_TRANSFER' => __('contract_payments.methods.bank_transfer'), 'CHECK' => __('contract_payments.methods.check'), 'CASH' => __('contract_payments.methods.cash')]" />
        <x-admin.input-solid name="payment_day" type="number" min="1" max="31" :label="__('contracts.payment_day')" placeholder="5" />
        <x-admin.input-solid name="rent_amount" type="number" step="0.01" min="0" :label="__('contracts.rent_amount')" id="rent_amount_input" />
    </x-admin.form-card>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const unitsData = @json($units->map(fn($unit) => [
                'id' => $unit->id,
                'name' => $unit->name,
                'property_id' => $unit->property_id,
                'rent_amount' => $unit->rent_amount,
            ])->values());

            const propertySelect = $('#contract_property_id');
            const unitSelect = $('#contract_unit_id');
            const rentInput = document.getElementById('rent_amount_input');
            const startInput = document.getElementById('contract_start_date');
            const durationInput = document.getElementById('duration_months');
            const endInput = document.getElementById('contract_end_date');

            const initialProperty = propertySelect.val();
            const initialUnit = '{{ old('unit_id') }}';

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
                }
                if (rentInput) {
                    rentInput.value = '';
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

            propertySelect.on('change', function () {
                refreshUnits(this.value);
            });

            unitSelect.on('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.dataset.rent && (!rentInput.value || rentInput.value === '0')) {
                    rentInput.value = selectedOption.dataset.rent;
                }
            });

            [startInput, durationInput].forEach(function (input) {
                if (input) {
                    input.addEventListener('change', updateEndDate);
                    input.addEventListener('keyup', updateEndDate);
                }
            });

            if (initialProperty) {
                refreshUnits(initialProperty, initialUnit);
            }
            updateEndDate();
        });
    </script>
@endpush
