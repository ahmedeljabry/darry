@extends('admin.layouts.master')
@section('title', __('locations.add_country'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('locations.countries_title') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('menu.dashboard') }}</a></li>
        <li class="breadcrumb-item text-muted"><a href="{{ route('admin.countries.index') }}" class="text-muted">{{ __('locations.countries_title') }}</a></li>
        <li class="breadcrumb-item text-muted">{{ __('locations.add_country') }}</li>
    </ul>
@endsection

@section('content')
    <x-admin.form-card :title="__('locations.add_country')" :action="route('admin.countries.store')" :back="route('admin.countries.index')">
        <x-admin.input-solid name="name" :label="__('locations.country_name')" required />
        <x-admin.input-solid name="code" :label="__('locations.country_code')" />
    </x-admin.form-card>
@endsection
