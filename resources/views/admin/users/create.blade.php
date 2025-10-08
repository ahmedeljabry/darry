@extends('admin.layouts.master')
@section('title', __('menu.users_create'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('menu.users') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="" class="text-muted">{{ __('menu.users_create') }}</a></li>
    </ul>
@endsection

@section('content')
    <x-admin.form-card :title="__('menu.users_create')" :action="route('admin.users.store')" :back="route('admin.users.index')">
        <div class="row">
            <div class="col-md-6"><x-admin.input-solid name="name" :label="__('tenants.full_name')" :placeholder="__('tenants.full_name')" required /></div>
            <div class="col-md-6"><x-admin.input-solid name="phone" :label="__('tenants.phone')" placeholder="05XXXXXXXX" /></div>
        </div>
        <div class="row">
            <div class="col-md-4"><x-admin.input-solid name="email" type="email" :label="__('auth.email')" placeholder="name@example.com" required /></div>
            <div class="col-md-4"><x-admin.input-solid name="password" type="password" :label="__('auth.password')" required /></div>
            <div class="col-md-4"><x-admin.input-solid name="password_confirmation" type="password" :label="__('auth.password')" /></div>
        </div>
        <div class="row">
            <div class="col-md-6"><x-admin.select-solid name="role" :label="__('roles.roles') ?? 'الدور'" :options="$roles" required /></div>
            <div class="col-md-6"><x-admin.select-solid name="status" :label="__('owners.status')" :options="['ACTIVE' => __('owners.active'), 'INACTIVE' => __('owners.inactive')]" required /></div>
        </div>
    </x-admin.form-card>
@endsection
