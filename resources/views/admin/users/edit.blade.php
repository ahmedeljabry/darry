@extends('admin.layouts.master')
@section('title', __('menu.users'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('menu.users') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="" class="text-muted">{{ __('menu.users') }}</a></li>
    </ul>
@endsection

@section('content')
    <x-admin.form-card :title="__('menu.users')" :action="route('admin.users.update', $user)" method="PUT" :back="route('admin.users.index')">
        @php($statusOptions = ['ACTIVE' => __('owners.active'), 'INACTIVE' => __('owners.inactive')])

        @include('admin.users.partials.form-fields', [
            'mode' => 'edit',
            'user' => $user,
            'canManageSystem' => $canManageSystem,
            'properties' => $properties,
            'currentPropertyId' => $currentPropertyId ?? null,
            'currentPropertyName' => $currentPropertyName ?? null,
            'roles' => $roles,
            'statusOptions' => $statusOptions,
        ])
    </x-admin.form-card>
@endsection
