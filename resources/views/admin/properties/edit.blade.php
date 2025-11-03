@extends('admin.layouts.master')
@section('title', __('properties.edit'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('properties.title') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="" class="text-muted">{{ __('properties.list') }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <x-admin.form-card :title="__('properties.edit')" :action="route('admin.properties.update', $property)" method="PUT" :back="route('admin.properties.index')" enctype="multipart/form-data">

        <x-admin.input-solid name="name" :label="__('properties.name')" :value="old('name', $property->name)" required />

        <x-admin.select-solid
            name="country_id"
            :label="__('properties.country')"
            :options="$countries"
            :value="old('country_id', $selectedCountryId)"
            data-role="country-select"
        />

        <x-admin.select-solid
            name="governorate_id"
            :label="__('properties.governorate')"
            :options="$governorates"
            :value="old('governorate_id', $selectedGovernorateId)"
            data-role="governorate-select"
        />

        <x-admin.select-solid
            name="state_id"
            :label="__('properties.state')"
            :options="$states"
            :value="old('state_id', $selectedStateId)"
            data-role="state-select"
        />

        <input type="hidden" name="country" value="{{ old('country', $property->country) }}" data-role="country-name">
        <input type="hidden" name="governorate" value="{{ old('governorate', $property->governorate) }}" data-role="governorate-name">
        <input type="hidden" name="state" value="{{ old('state', $property->state) }}" data-role="state-name">

        <x-admin.input-solid name="city" :label="__('properties.address')" :value="old('city', $property->city)" />

        <x-admin.input-solid name="coordinates" :label="__('properties.coordinates')" :value="old('coordinates', $property->coordinates)" />

        <x-admin.input-solid name="area_sqm" type="number" :label="__('properties.area_sqm')" :value="old('area_sqm', $property->area_sqm)" min="0" />

        @php
            $useTypeOptions = collect(App\Domain\Enums\PropertyUseType::cases())
                ->mapWithKeys(fn($case) => [$case->value => __("properties.use_types." . $case->value)])
                ->toArray();
        @endphp
        <x-admin.select-solid
            name="use_type"
            :label="__('properties.use_type')"
            :options="$useTypeOptions"
            :value="old('use_type', $property->use_type?->value ?? $property->use_type)"
        />

        <x-admin.select-solid
            name="facilities[]"
            :label="__('properties.facilities')"
            :options="$facilities"
            :value="old('facilities', $property->facilities?->pluck('id')->all() ?? [])"
            multiple
        />

        @php
            $thumbPath = $property->thumbnail;
            $thumbUrl = $thumbPath ? \Illuminate\Support\Facades\Storage::url($thumbPath) : null;
            $imgPaths = $property->images ?? [];
            $imgUrls = collect($imgPaths)->map(fn($p) => \Illuminate\Support\Facades\Storage::url($p))->all();
        @endphp
        <x-admin.dropzone name="thumbnail" :label="__('properties.thumbnail')" accept="image/*" :existing="$thumbPath" :existing-urls="$thumbUrl" />
        <x-admin.dropzone name="images" :label="__('properties.images')" accept="image/*" multiple :existing="$imgPaths" :existing-urls="$imgUrls" />
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
                if (!selectEl || !hiddenInput) return;
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

                if ($el.hasClass('select2-hidden-accessible')) {
                    $el.trigger('change.select2');
                }
            };

            const setSelectValue = function (selectEl, value, hiddenInput = null, triggerChange = true) {
                const $el = $(selectEl);
                const normalized = value === null || typeof value === 'undefined' ? '' : String(value);

                $el.val(normalized);

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

            stateSelect.addEventListener('change', handleStateChange);
            $(stateSelect).on('select2:select', handleStateChange);

            const initialCountry = @json(old('country_id', $selectedCountryId));
            const initialGovernorate = @json(old('governorate_id', $selectedGovernorateId));
            const initialState = @json(old('state_id', $selectedStateId));

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
