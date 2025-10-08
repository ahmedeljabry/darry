@extends('admin.layouts.master')
@section('title', __('owners.create'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('owners.title') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="" class="text-muted">{{ __('owners.create') }}</a></li>
    </ul>
@endsection

@section('content')
    <x-admin.form-card :title="__('owners.create')" :action="route('admin.owners.store')" :back="route('admin.owners.index')">
            <x-admin.input-solid name="full_name" :label="__('owners.full_name')" required />
            <x-admin.input-solid name="id_or_cr" :label="__('owners.id_or_cr')" />
 
            <x-admin.input-solid name="email" type="email" :label="__('owners.email')" />
            <x-admin.input-solid name="password" type="password" :label="__('owners.password')" />
            <x-admin.input-solid name="phone" :label="__('owners.phone')" />
        <x-admin.input-solid name="address" :label="__('owners.address')" />
                <x-admin.select-solid name="owner_type" :label="__('owners.type')" :options="['PERSONAL' => __('owners.types.PERSONAL'),'COMMERCIAL' => __('owners.types.COMMERCIAL')]" />
                <x-admin.select-solid name="status" :label="__('owners.status')" :options="['ACTIVE' => __('owners.active'),'INACTIVE' => __('owners.inactive')]" value="ACTIVE" />
    </x-admin.form-card>
@endsection

