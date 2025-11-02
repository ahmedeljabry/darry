@extends('admin.layouts.master')
@section('title', __('locations.add_state'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('locations.states_title') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('menu.dashboard') }}</a></li>
        <li class="breadcrumb-item text-muted"><a href="{{ route('admin.states.index') }}" class="text-muted">{{ __('locations.states_title') }}</a></li>
        <li class="breadcrumb-item text-muted">{{ __('locations.add_state') }}</li>
    </ul>
@endsection

@section('content')
    <x-admin.form-card :title="__('locations.add_state')" :action="route('admin.states.store')" :back="route('admin.states.index')">
        <x-admin.select-solid
            name="country_id"
            :label="__('locations.country')"
            :options="$countries"
            :value="old('country_id', $selectedCountry)"
            required
            data-role="country-select"
        />
        <x-admin.select-solid
            name="governorate_id"
            :label="__('locations.governorate')"
            :options="$governorates"
            :value="old('governorate_id')"
            required
            data-role="governorate-select"
        />
        <x-admin.input-solid name="name" :label="__('locations.state_name')" :value="old('name')" required />
    </x-admin.form-card>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const countrySelect = document.querySelector('[data-role="country-select"]');
            const governorateSelect = document.querySelector('[data-role="governorate-select"]');
            if (!countrySelect || !governorateSelect) {
                return;
            }

            const placeholderGovernorate = @js(__('locations.choose_governorate'));

            const populateGovernorates = function (countryId, selectedValue = '') {
                if (!countryId) {
                    governorateSelect.innerHTML = `<option value="">${placeholderGovernorate}</option>`;
                    $(governorateSelect).val('').trigger('change');
                    return;
                }
                fetch(`{{ url('admin/locations/countries') }}/${countryId}/governorates`, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                    .then(response => response.json())
                    .then(data => {
                        const placeholder = governorateSelect.dataset.placeholder || placeholderGovernorate;
                        governorateSelect.innerHTML = `<option value="">${placeholder}</option>`;
                        (data.items || []).forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.name;
                            governorateSelect.appendChild(option);
                        });
                        $(governorateSelect).val(selectedValue).trigger('change');
                    });
            };

            countrySelect.addEventListener('change', function () {
                populateGovernorates(this.value);
            });

            const initialCountry = @json(old('country_id', $selectedCountry));
            const initialGovernorate = @json(old('governorate_id'));

            if (initialCountry) {
                populateGovernorates(initialCountry, initialGovernorate);
            } else {
                populateGovernorates('');
            }
        });
    </script>
@endpush
