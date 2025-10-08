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

        <x-admin.input-solid name="country" :label="__('properties.country')" :placeholder="__('properties.country')" :required='true' />

        <x-admin.input-solid name="governorate" :label="__('properties.governorate')" :placeholder="__('properties.governorate')" required />

        <x-admin.input-solid name="state" :label="__('properties.state')" :placeholder="__('properties.state')" required />

        <x-admin.input-solid name="city" :label="__('properties.city')" :placeholder="__('properties.city')" required />
        
        <x-admin.input-solid name="coordinates" :label="__('properties.coordinates')" required />

        <x-admin.input-solid name="area_sqm" type="number" :label="__('properties.area_sqm')" placeholder="120" min="0" />

        <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('properties.use_type') }}</label>
            <div class="col-lg-8">
                @php $useOld = old('use_type'); @endphp
                <div class="d-flex flex-wrap gap-2" id="useTypeGroup">
                    @foreach(['RESIDENTIAL','MIXED','COMMERCIAL','INDUSTRIAL','AGRICULTURAL'] as $val)
                        <label class="btn btn-outline-primary btn-sm {{ $useOld === $val ? 'active' : '' }}">
                            <input type="radio" name="use_type" value="{{ $val }}" {{ $useOld === $val ? 'checked' : '' }}>
                            {{ __('properties.use_types.'.$val) }}
                        </label>
                    @endforeach
                </div>
                @error('use_type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
        </div>

        <x-admin.select-solid name="facilities[]" :label="__('properties.facilities')" :options="$facilities" :placeholder="__('properties.facilities')" multiple />

        <x-admin.dropzone name="thumbnail" :label="__('properties.thumbnail')" accept="image/*" />

        <x-admin.dropzone name="images" :label="__('properties.images')" accept="image/*" multiple />

    </x-admin.form-card>
@endsection
