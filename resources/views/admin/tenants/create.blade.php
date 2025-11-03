@extends('admin.layouts.master')

@section('title', __('tenants.create'))
@section('breadcrumbs')
    <!--begin::Page Title-->
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('tenants.title') }}</h5>
    <!--end::Page Title-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="" class="text-muted">{{ __('tenants.create') }}</a>
        </li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('content')
    <x-admin.form-card :title="__('tenants.create')" :action="route('admin.tenants.store')" :back="route('admin.tenants.index')">
        <x-admin.select-solid name="tenant_type" :label="__('tenants.tenant_type')" :options="['PERSONAL' => __('tenants.tenant_types.PERSONAL'),'COMMERCIAL' => __('tenants.tenant_types.COMMERCIAL')]" required />

        <x-admin.input-solid name="full_name" :label="__('tenants.full_name')" placeholder="{{ __('tenants.full_name') }}" required />
        <x-admin.input-solid name="national_id_or_cr" :label="__('tenants.national_id_or_cr')" placeholder="{{ __('tenants.national_id_or_cr') }}" />
        <x-admin.input-solid name="work_or_study_place" :label="__('tenants.work_or_study_place')" placeholder="{{ __('tenants.work_or_study_place') }}" />

        <x-admin.input-solid name="email" type="email" :label="__('tenants.email')" placeholder="name@example.com" />
        <x-admin.input-solid name="phone" :label="__('tenants.phone')" placeholder="05XXXXXXXX" />
        <x-admin.input-solid name="phone2" :label="__('tenants.phone2')" placeholder="05XXXXXXXX" />
        <x-admin.textarea-solid name="address" :label="__('tenants.address')" />

        <hr>
        <h5 class="mb-3">{{ __('tenants.relatives') }}</h5>
        <div id="tenantRelatives">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-custom mb-4">
                        <div class="card-header"><div class="card-title">{{ __('tenants.relative_1') }}</div></div>
                        <div class="card-body">
                            <x-admin.input-solid name="relatives[0][name]" :label="__('tenants.relative_name')" />
                            <x-admin.input-solid name="relatives[0][id_no]" :label="__('tenants.relative_id')" />
                            <x-admin.input-solid name="relatives[0][phone]" :label="__('tenants.relative_phone')" />
                            <x-admin.input-solid name="relatives[0][kinship]" :label="__('tenants.relative_kinship')" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-custom mb-4">
                        <div class="card-header"><div class="card-title">{{ __('tenants.relative_2') }}</div></div>
                        <div class="card-body">
                            <x-admin.input-solid name="relatives[1][name]" :label="__('tenants.relative_name')" />
                            <x-admin.input-solid name="relatives[1][id_no]" :label="__('tenants.relative_id')" />
                            <x-admin.input-solid name="relatives[1][phone]" :label="__('tenants.relative_phone')" />
                            <x-admin.input-solid name="relatives[1][kinship]" :label="__('tenants.relative_kinship')" />
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
