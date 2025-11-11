@extends('admin.layouts.master')
@section('title', __('owners.edit'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('owners.title') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="" class="text-muted">{{ __('owners.edit') }}</a></li>
    </ul>
@endsection

@section('content')
    <x-admin.form-card :title="__('owners.edit')" :action="route('admin.owners.update', $owner)" method="PUT" :back="route('admin.owners.index')">
        @include('admin.owners.partials.property-field', [
            'canManageSystem' => $canManageSystem ?? false,
            'properties' => $properties ?? [],
            'currentPropertyId' => $currentPropertyId ?? null,
            'currentPropertyName' => $currentPropertyName ?? null,
            'value' => old('property_id', $owner->property_id),
        ])

        <x-admin.input-solid name="full_name" :label="__('owners.full_name')" :value="old('full_name', $owner->full_name)" required />
        <x-admin.input-solid name="id_or_cr" :label="__('owners.id_or_cr')" :value="old('id_or_cr', $owner->id_or_cr)" />
        <x-admin.input-solid name="email" type="email" :label="__('owners.email')" :value="old('email', $owner->email)" />
        <x-admin.input-solid name="phone" :label="__('owners.phone')" :value="old('phone', $owner->phone)" />
        <x-admin.input-solid name="address" :label="__('owners.address')" :value="old('address', $owner->address)" />

        <x-admin.select-solid name="owner_type" :label="__('owners.type')" :options="['PERSONAL' => __('owners.types.PERSONAL'),'COMMERCIAL' => __('owners.types.COMMERCIAL')]" :value="old('owner_type', $owner->owner_type)" />
        <x-admin.select-solid name="status" :label="__('owners.status')" :options="['ACTIVE' => __('owners.active'),'INACTIVE' => __('owners.inactive')]" :value="old('status', $owner->status)" />
    </x-admin.form-card>
@endsection
