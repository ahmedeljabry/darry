@extends('admin.layouts.master')
@section('title', __('facilities.edit'))
@section('breadcrumbs')
    <!--begin::Page Title-->
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('facilities.title') }}</h5>
    <!--end::Page Title-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="" class="text-muted">{{ __('facilities.list') }}</a>
        </li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')
    <x-admin.form-card :title="__('facilities.edit')" :action="route('admin.facilities.update', $facility)" method="PUT" :back="route('admin.facilities.index')">
        <x-admin.input-solid name="name" :label="__('facilities.name')" :value="$facility->name" required />
        <x-admin.input-solid name="description" :label="__('facilities.description')" :value="$facility->description" />
    </x-admin.form-card>
@endsection
