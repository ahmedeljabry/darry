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

        <x-admin.input-solid name="country" :label="__('properties.country')" :value="old('country', $property->country)" required />

        <x-admin.input-solid name="governorate" :label="__('properties.governorate')" :value="old('governorate', $property->governorate)" required />

        <x-admin.input-solid name="state" :label="__('properties.state')" :value="old('state', $property->state)" required />

        <x-admin.input-solid name="city" :label="__('properties.city')" :value="old('city', $property->city)" required />

        <x-admin.input-solid name="coordinates" :label="__('properties.coordinates')" :value="old('coordinates', $property->coordinates)" required />

        <x-admin.input-solid name="area_sqm" type="number" :label="__('properties.area_sqm')" :value="old('area_sqm', $property->area_sqm)" min="0" />

        <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('properties.use_type') }}</label>
            <div class="col-lg-8">
                @php
                    $useOld = old('use_type', $property->use_type?->value ?? $property->use_type);
                @endphp
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
