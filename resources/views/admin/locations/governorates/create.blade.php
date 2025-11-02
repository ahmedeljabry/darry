@extends('admin.layouts.master')
@section('title', __('locations.add_governorate'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('locations.governorates_title') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('menu.dashboard') }}</a></li>
        <li class="breadcrumb-item text-muted"><a href="{{ route('admin.governorates.index') }}" class="text-muted">{{ __('locations.governorates_title') }}</a></li>
        <li class="breadcrumb-item text-muted">{{ __('locations.add_governorate') }}</li>
    </ul>
@endsection

@section('content')
    <x-admin.form-card :title="__('locations.add_governorate')" :action="route('admin.governorates.store')" :back="route('admin.governorates.index')">
        <x-admin.select-solid name="country_id" :label="__('locations.country')" :options="$countries" required />
        <x-admin.input-solid name="name" :label="__('locations.governorate_name')" required />
    </x-admin.form-card>
@endsection
