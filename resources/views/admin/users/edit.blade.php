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
        <div class="row">
            <div class="col-md-6"><x-admin.input-solid name="name" :label="__('tenants.full_name')" :value="$user->name" required /></div>
            <div class="col-md-6"><x-admin.input-solid name="phone" :label="__('tenants.phone')" :value="$user->phone" /></div>
        </div>
        <div class="row">
            <div class="col-md-4"><x-admin.input-solid name="email" type="email" :label="__('auth.email')" :value="$user->email" required /></div>
            <div class="col-md-4"><x-admin.input-solid name="password" type="password" :label="__('auth.password')" /></div>
            <div class="col-md-4"><x-admin.input-solid name="password_confirmation" type="password" :label="__('auth.password')" /></div>
        </div>
        <div class="row">
            @php($currentRole = $user->roles->pluck('name')->first())
            <div class="col-md-6"><x-admin.select-solid name="role" :label="__('roles.roles') ?? 'الدور'" :options="$roles" :value="$currentRole" required /></div>
            <div class="col-md-6"><x-admin.select-solid name="status" :label="__('owners.status')" :options="['ACTIVE' => __('owners.active'), 'INACTIVE' => __('owners.inactive')]" :value="$user->status" required /></div>
        </div>
    </x-admin.form-card>
@endsection
