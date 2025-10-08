@extends('admin.layouts.master')
@section('title', __('expenses.title'))
@include('admin.layouts.partials._datatable')

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('expenses.title') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="" class="text-muted">{{ __('expenses.list') }}</a></li>
    </ul>
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header py-3">
            <div class="card-title"><h3 class="card-label">{{ __('expenses.title') }}</h3></div>
            <div class="card-toolbar">
                <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary">{{ __('expenses.create') }}</a>
            </div>
        </div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
@endsection

