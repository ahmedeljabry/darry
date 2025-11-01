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

        <x-admin.select-solid name="country" :label="__('properties.country')" :options="App\Helpers\Place::countries()" :value="old('country',$property->country)" :searchable="true" />

        <x-admin.select-solid name="governorate" :label="__('properties.governorate')" :options="App\Helpers\Place::governorates()" :value="old('governorate',$property->governorate)" :searchable="true" />

        <x-admin.select-solid name="state" :label="__('properties.state')" :options="App\Helpers\Place::states()" :value="old('state',$property->state)" :searchable="true" />

        <x-admin.input-solid name="city" :label="__('properties.address')" :value="old('city', $property->city)" />

        <x-admin.input-solid name="coordinates" :label="__('properties.coordinates')" :value="old('coordinates', $property->coordinates)" />

        <x-admin.input-solid name="area_sqm" type="number" :label="__('properties.area_sqm')" :value="old('area_sqm', $property->area_sqm)" min="0" />

        <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('properties.use_type') }}</label>
            <div class="col-lg-8">
                <x-admin.select-solid name="use_type" :label="__('properties.use_type')" :options="App\Domain\Enums\PropertyUseType::cases()" :value="old('use_type', $property->use_type?->value ?? $property->use_type)" :option-label="fn($u)=>__("properties.use_types.".$u->value)" :option-value="fn($u)=>$u->value" />
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
