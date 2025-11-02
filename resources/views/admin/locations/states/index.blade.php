@extends('admin.layouts.master')
@section('title', __('locations.states_title'))
@include('admin.layouts.partials._datatable')

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('locations.states_title') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('menu.dashboard') }}</a></li>
        <li class="breadcrumb-item text-muted"><span class="text-muted">{{ __('locations.location_management') }}</span></li>
        <li class="breadcrumb-item text-muted">{{ __('locations.states_title') }}</li>
    </ul>
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header py-5">
            <div class="card-title">
                <h3 class="card-label">{{ __('locations.states_title') }}</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('admin.states.create') }}" class="btn btn-primary font-weight-bolder">
                    <i class="la la-plus ml-1"></i>{{ __('locations.add_state') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
@endsection
