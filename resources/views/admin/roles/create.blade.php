@extends('admin.layouts.master')
@section('title', __('menu.roles_create'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('menu.roles') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="" class="text-muted">{{ __('menu.roles_create') }}</a></li>
    </ul>
@endsection

@section('content')
    <x-admin.form-card :title="__('menu.roles_create')" :action="route('admin.roles.store')" :back="route('admin.roles.index')">
        <x-admin.input-solid name="name" :label="__('menu.roles')" :placeholder="__('menu.roles')" required />

        <div class="mb-3">
            @include('admin.roles._permissions', ['permissionGroups' => $permissionGroups, 'selected' => []])
        </div>
    </x-admin.form-card>
@endsection
