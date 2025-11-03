@extends('admin.layouts.master')

@section('title', __('properties.create'))
@section('breadcrumbs')
    <!--begin::Page Title-->
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('properties.title') }}</h5>
    <!--end::Page Title-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="" class="text-muted">{{ __('properties.list') }}</a>
        </li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('content')
    <x-admin.form-card :title="__('properties.create')" :action="route('admin.properties.store')" :back="url()->previous()" enctype="multipart/form-data">

        <x-admin.input-solid name="name" :label="__('properties.name')" :placeholder="__('properties.name')" :required='true' />

        <x-admin.select-solid
            name="country_id"
            :label="__('properties.country')"
            :options="$countries"
            :value="old('country_id')"
            data-role="country-select"
        />

        <x-admin.select-solid
            name="governorate_id"
            :label="__('properties.governorate')"
            :options="[]"
            :value="old('governorate_id')"
            data-role="governorate-select"
        />

        <x-admin.select-solid
            name="state_id"
            :label="__('properties.state')"
            :options="[]"
            :value="old('state_id')"
            data-role="state-select"
        />

        <input type="hidden" name="country" value="{{ old('country') }}" data-role="country-name">
        <input type="hidden" name="governorate" value="{{ old('governorate') }}" data-role="governorate-name">
        <input type="hidden" name="state" value="{{ old('state') }}" data-role="state-name">

        <x-admin.input-solid name="city" :label="__('properties.address')" :placeholder="__('properties.address')" />

        <x-admin.input-solid name="coordinates" :label="__('properties.coordinates')" />

        <x-admin.input-solid name="area_sqm" type="number" :label="__('properties.area_sqm')" placeholder="120" min="0" />

        @php
            $useTypeOptions = collect(App\Domain\Enums\PropertyUseType::cases())
                ->mapWithKeys(fn($case) => [$case->value => __("properties.use_types." . $case->value)])
                ->toArray();
        @endphp
        <x-admin.select-solid name="use_type" :label="__('properties.use_type')" :options="$useTypeOptions" :value="old('use_type')" />

        <x-admin.select-solid name="facilities[]" :label="__('properties.facilities')" :options="$facilities" :placeholder="__('properties.facilities')" multiple />

        <x-admin.dropzone name="thumbnail" :label="__('properties.thumbnail')" accept="image/*" />

        <x-admin.dropzone name="images" :label="__('properties.images')" accept="image/*" multiple />

    </x-admin.form-card>
@endsection

@push('scripts')
    <script>
        $(function () {
            const countrySelect = document.querySelector('[data-role="country-select"]');
            const governorateSelect = document.querySelector('[data-role="governorate-select"]');
            const stateSelect = document.querySelector('[data-role="state-select"]');
            const countryNameInput = document.querySelector('[data-role="country-name"]');
            const governorateNameInput = document.querySelector('[data-role="governorate-name"]');
            const stateNameInput = document.querySelector('[data-role="state-name"]');

            if (!countrySelect || !governorateSelect || !stateSelect) {
                return;
            }

            const setSelectedName = function (selectEl, hiddenInput) {
                if (!selectEl || !hiddenInput) {
                    return;
                }
                const option = selectEl.options[selectEl.selectedIndex];
                hiddenInput.value = option && option.value ? option.text : '';
            };

            const placeholders = {
                country: countrySelect?.dataset.placeholder ?? '',
                governorate: @js(__('properties.governorate')),
                state: @js(__('properties.state')),
            };

            const populateSelect = function (selectEl, items, fallbackPlaceholderKey) {
                const $el = $(selectEl);
                const current = selectEl.dataset.placeholder || placeholders[fallbackPlaceholderKey] || '';
                const isMultiple = $el.prop('multiple');

                $el.empty();

                if (!isMultiple) {
                    const placeholderOption = new Option(current, '', false, false);
                    placeholderOption.dataset.placeholder = 'true';
                    $el.append(placeholderOption);
                }

                items.forEach(function (item) {
                    const option = new Option(item.name, item.id, false, false);
                    $el.append(option);
                });

                // refresh select2 UI without triggering change callback yet
                if ($el.hasClass('select2-hidden-accessible')) {
                    $el.trigger('change.select2');
                }
            };

            const setSelectValue = function (selectEl, value, hiddenInput = null, triggerChange = true) {
                const $el = $(selectEl);
                const normalized = value === null || typeof value === 'undefined' ? '' : String(value);

                if (normalized === '') {
                    $el.val('');
                } else {
                    $el.val(normalized);
                }

                if ($el.hasClass('select2-hidden-accessible')) {
                    $el.trigger('change.select2');
                }

                if (triggerChange) {
                    $el.trigger('change');
                }

                if (hiddenInput) {
                    setSelectedName(selectEl, hiddenInput);
                }
            };

            const fetchGovernorates = function (countryId, selectedValue = '') {
                if (!countryId) {
                    populateSelect(governorateSelect, [], 'governorate');
                    populateSelect(stateSelect, [], 'state');
                    setSelectValue(governorateSelect, '', governorateNameInput, false);
                    setSelectValue(stateSelect, '', stateNameInput, false);
                    return;
                }

                fetch(`{{ url('admin/locations/countries') }}/${countryId}/governorates`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        credentials: 'same-origin',
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        populateSelect(governorateSelect, data.items || [], 'governorate');
                        setSelectValue(governorateSelect, selectedValue || '', governorateNameInput, !!(selectedValue || ''));
                    })
                    .catch(error => {
                        console.error('Failed to fetch governorates', error);
                    });
            };

            const fetchStates = function (governorateId, selectedValue = '') {
                if (!governorateId) {
                    populateSelect(stateSelect, [], 'state');
                    setSelectValue(stateSelect, '', stateNameInput, false);
                    return;
                }

                fetch(`{{ url('admin/locations/governorates') }}/${governorateId}/states`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        credentials: 'same-origin',
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        populateSelect(stateSelect, data.items || [], 'state');
                        setSelectValue(stateSelect, selectedValue || '', stateNameInput, !!(selectedValue || ''));
                    })
                    .catch(error => {
                        console.error('Failed to fetch states', error);
                    });
            };

            const handleCountryChange = function (value) {
                console.debug('[properties] country changed', value);
                setSelectedName(countrySelect, countryNameInput);
                fetchGovernorates(value);
            };

            const handleGovernorateChange = function (value) {
                console.debug('[properties] governorate changed', value);
                setSelectedName(governorateSelect, governorateNameInput);
                fetchStates(value);
            };

            const handleStateChange = function () {
                setSelectedName(stateSelect, stateNameInput);
            };

            countrySelect.addEventListener('change', function () {
                handleCountryChange(this.value);
            });
            $(countrySelect).on('select2:select', function (e) {
                handleCountryChange(e.params.data.id);
            });

            governorateSelect.addEventListener('change', function () {
                handleGovernorateChange(this.value);
            });
            $(governorateSelect).on('select2:select', function (e) {
                handleGovernorateChange(e.params.data.id);
            });

            setSelectedName(countrySelect, countryNameInput);
            setSelectedName(governorateSelect, governorateNameInput);
            stateSelect.addEventListener('change', handleStateChange);
            $(stateSelect).on('select2:select', handleStateChange);

            const initialCountry = @json(old('country_id'));
            const initialGovernorate = @json(old('governorate_id'));
            const initialState = @json(old('state_id'));

            if (initialCountry) {
                fetchGovernorates(initialCountry, initialGovernorate);
            }
            if (initialGovernorate) {
                fetchStates(initialGovernorate, initialState);
            }

            setSelectedName(countrySelect, countryNameInput);
            setSelectedName(governorateSelect, governorateNameInput);
            setSelectedName(stateSelect, stateNameInput);
        });
    </script>
@endpush
