@extends('admin.layouts.master')

@section('title', __('tenants.edit_tenants'))
@section('breadcrumbs')
    <!--begin::Page Title-->
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('tenants.title') }}</h5>
    <!--end::Page Title-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="" class="text-muted">{{ __('tenants.edit_tenants') }}</a>
        </li>
    </ul>
    <!--end::Breadcrumb-->
@endsection


@section('content')
    <x-admin.form-card :title="__('tenants.edit_tenants')" :action="route('admin.tenants.update', $tenant)" method="PUT" :back="route('admin.tenants.index')">
        @include('admin.tenants.partials.property-field', [
            'canManageSystem' => $canManageSystem ?? false,
            'properties' => $properties ?? [],
            'currentPropertyId' => $currentPropertyId ?? null,
            'currentPropertyName' => $currentPropertyName ?? null,
            'value' => old('property_id', $tenant->property_id),
        ])

        <x-admin.select-solid name="tenant_type" :label="__('tenants.tenant_type')" :options="['PERSONAL' => __('tenants.tenant_types.PERSONAL'),'COMMERCIAL' => __('tenants.tenant_types.COMMERCIAL')]" :value="old('tenant_type', $tenant->tenant_type)" required />

        <x-admin.input-solid name="full_name" :label="__('tenants.full_name')" :value="$tenant->full_name" placeholder="{{ __('tenants.full_name') }}" required />
        <x-admin.input-solid name="national_id_or_cr" :label="__('tenants.national_id_or_cr')" :value="old('national_id_or_cr', $tenant->national_id_or_cr)" />
        <x-admin.input-solid name="work_or_study_place" :label="__('tenants.work_or_study_place')" :value="old('work_or_study_place', $tenant->work_or_study_place)" />

        <x-admin.input-solid name="email" type="email" :label="__('tenants.email')" :value="$tenant->email" placeholder="name@example.com" />
        <x-admin.input-solid name="phone" :label="__('tenants.phone')" :value="$tenant->phone" placeholder="05XXXXXXXX" />
        <x-admin.input-solid name="phone2" :label="__('tenants.phone2')" :value="old('phone2', $tenant->phone2)" placeholder="05XXXXXXXX" />
        <x-admin.textarea-solid name="address" :label="__('tenants.address')" :value="old('address', $tenant->address)" />

        @php($rels = $tenant->relatives()->take(2)->get()->values())
        <hr>
        <div id="tenantRelatives">
            <h5 class="mb-3">{{ __('tenants.relatives') }}</h5>
            <div class="row">
            <div class="col-md-6">
                <div class="card card-custom mb-4">
                    <div class="card-header"><div class="card-title">{{ __('tenants.relative_1') }}</div></div>
                    <div class="card-body">
                        <x-admin.input-solid name="relatives[0][name]" :label="__('tenants.relative_name')" :value="old('relatives.0.name', $rels[0]->name ?? '')" />
                        <x-admin.input-solid name="relatives[0][id_no]" :label="__('tenants.relative_id')" :value="old('relatives.0.id_no', $rels[0]->id_no ?? '')" />
                        <x-admin.input-solid name="relatives[0][phone]" :label="__('tenants.relative_phone')" :value="old('relatives.0.phone', $rels[0]->phone ?? '')" />
                        <x-admin.input-solid name="relatives[0][kinship]" :label="__('tenants.relative_kinship')" :value="old('relatives.0.kinship', $rels[0]->kinship ?? '')" />
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-custom mb-4">
                    <div class="card-header"><div class="card-title">{{ __('tenants.relative_2') }}</div></div>
                    <div class="card-body">
                        <x-admin.input-solid name="relatives[1][name]" :label="__('tenants.relative_name')" :value="old('relatives.1.name', $rels[1]->name ?? '')" />
                        <x-admin.input-solid name="relatives[1][id_no]" :label="__('tenants.relative_id')" :value="old('relatives.1.id_no', $rels[1]->id_no ?? '')" />
                        <x-admin.input-solid name="relatives[1][phone]" :label="__('tenants.relative_phone')" :value="old('relatives.1.phone', $rels[1]->phone ?? '')" />
                        <x-admin.input-solid name="relatives[1][kinship]" :label="__('tenants.relative_kinship')" :value="old('relatives.1.kinship', $rels[1]->kinship ?? '')" />
                    </div>
                </div>
            </div>
        </div>
        </div>
    </x-admin.form-card>
@endsection

@push('scripts')
    <script>
        $(function () {
            const tenantTypeSelect = document.querySelector('select[name="tenant_type"]');
            const relativesSection = document.getElementById('tenantRelatives');
            if (!tenantTypeSelect || !relativesSection) {
                return;
            }

            const relativeInputs = relativesSection.querySelectorAll('input, select, textarea');

            const toggleRelatives = function (type) {
                const isCommercial = type === 'COMMERCIAL';
                relativesSection.classList.toggle('d-none', isCommercial);
                relativeInputs.forEach(function (el) {
                    el.disabled = isCommercial;
                });
            };

            const handleChange = function (value) {
                toggleRelatives(value || '');
            };

            tenantTypeSelect.addEventListener('change', function () {
                handleChange(this.value);
            });

            const $select = $(tenantTypeSelect);
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.on('select2:select select2:clear', function (e) {
                    handleChange(e.params && e.params.data ? e.params.data.id : tenantTypeSelect.value);
                });
            }

            handleChange(tenantTypeSelect.value);
        });
    </script>
@endpush
