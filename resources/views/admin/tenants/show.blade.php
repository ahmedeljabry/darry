@extends('admin.layouts.master')

@section('title', __('tenants.title'))
@section('breadcrumbs')
    <!--begin::Page Title-->
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('tenants.title') }}</h5>
    <!--end::Page Title-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="" class="text-muted">{{ __('tenants.title') }}</a>
        </li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">{{ $tenant->full_name }}</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn btn-sm btn-primary">{{ __('messages.edit') }}</a>
                <a href="{{ route('admin.tenants.index') }}" class="btn btn-sm btn-light">{{ __('messages.back') }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-10">
                <div class="col-md-6">
                    <div class="mb-4"><strong>{{ __('tenants.tenant_type') }}:</strong> {{ __('tenants.tenant_types.'.($tenant->tenant_type ?? 'PERSONAL')) }}</div>
                    <div class="mb-4"><strong>{{ __('tenants.full_name') }}:</strong> {{ $tenant->full_name }}</div>
                    <div class="mb-4"><strong>{{ __('tenants.national_id_or_cr') }}:</strong> {{ $tenant->national_id_or_cr }}</div>
                    <div class="mb-4"><strong>{{ __('tenants.work_or_study_place') }}:</strong> {{ $tenant->work_or_study_place }}</div>
                </div>
                <div class="col-md-6">
                    <div class="mb-4"><strong>{{ __('tenants.email') }}:</strong> {{ $tenant->email }}</div>
                    <div class="mb-4"><strong>{{ __('tenants.phone') }}:</strong> {{ $tenant->phone }}</div>
                    <div class="mb-4"><strong>{{ __('tenants.phone2') }}:</strong> {{ $tenant->phone2 }}</div>
                    <div class="mb-4"><strong>{{ __('tenants.address') }}:</strong> {{ $tenant->address }}</div>
                </div>
            </div>

            <h5 class="mb-4">{{ __('tenants.relatives') }}</h5>
            <div class="row">
                @foreach($tenant->relatives->take(2) as $index => $rel)
                    <div class="col-md-6">
                        <div class="card card-custom mb-6">
                            <div class="card-header"><div class="card-title">{{ $index === 0 ? __('tenants.relative_1') : __('tenants.relative_2') }}</div></div>
                            <div class="card-body">
                                <div class="mb-3"><strong>{{ __('tenants.relative_name') }}:</strong> {{ $rel->name }}</div>
                                <div class="mb-3"><strong>{{ __('tenants.relative_id') }}:</strong> {{ $rel->id_no }}</div>
                                <div class="mb-3"><strong>{{ __('tenants.relative_phone') }}:</strong> {{ $rel->phone }}</div>
                                <div class="mb-3"><strong>{{ __('tenants.relative_kinship') }}:</strong> {{ $rel->kinship }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if($tenant->relatives->isEmpty())
                    <div class="col-12 text-muted">—</div>
                @endif
            </div>
        </div>
    </div>
@endsection

