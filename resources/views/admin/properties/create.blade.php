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

        <x-admin.select-solid name="country" :label="__('properties.country')" :options="App\Helpers\Place::countries()" :placeholder="__('properties.country')" :searchable="true" />

        <x-admin.select-solid name="governorate" :label="__('properties.governorate')" :options="App\Helpers\Place::governorates()" :placeholder="__('properties.governorate')" :searchable="true" />

        <x-admin.select-solid name="state" :label="__('properties.state')" :options="App\Helpers\Place::states()" :placeholder="__('properties.state')" :searchable="true" />

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
