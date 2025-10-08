@extends('admin.layouts.master')
@section('title', __('units.show'))
@section('breadcrumbs')
    <!--begin::Page Title-->
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('units.show') }}</h5>
    <!--end::Page Title-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:void();" class="text-muted">{{ __('units.title') }}</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:void();" class="text-muted">{{ __('units.show') }}</a>
        </li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('content')
    @php
        $typeVal = $unit->unit_type instanceof \App\Domain\Enums\UnitType ? $unit->unit_type->value : (string) $unit->unit_type;
        $rentVal = $unit->rent_type instanceof \App\Domain\Enums\RentType ? $unit->rent_type->value : (string) $unit->rent_type;
        $statusVal = $unit->status instanceof \App\Domain\Enums\UnitStatus ? $unit->status->value : (string) $unit->status;
        $statusBadge = $statusVal === 'ACTIVE' ? 'label-success' : 'label-light-danger';
    @endphp

    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0 d-flex align-items-center justify-content-between">
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">{{ $unit->name }}</h3>
                <div class="text-muted mt-1">
                    {{ __('units.types.' . $typeVal) }} •
                    <span class="label label-lg font-weight-bold label-inline {{ $statusBadge }}">{{ __('units.statuses.' . $statusVal) }}</span>
                </div>
            </div>
            <div class="card-toolbar">
                @include('admin.layouts.partials._actions', [
                    'showRoute' => null,
                    'editRoute' => route('admin.units.edit', $unit),
                    'deleteRoute' => route('admin.units.destroy', $unit),
                ])
            </div>
        </div>

        <div class="card-body border-top p-9">
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('units.name') }}</label>
                <div class="col-lg-8">
                    <div class="fw-bold">{{ $unit->name }}</div>
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('units.unit_type') }}</label>
                <div class="col-lg-8">
                    <div class="fw-bold">{{ __('units.types.' . $typeVal) }}</div>
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('units.capacity') }}</label>
                <div class="col-lg-8">
                    <div class="fw-bold">{{ $unit->capacity }}</div>
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('units.rent_type') }}</label>
                <div class="col-lg-8">
                    <div class="fw-bold">{{ __('units.rent_types.' . $rentVal) }}</div>
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('units.rent_amount') }}</label>
                <div class="col-lg-8">
                    <div class="fw-bold">{{ number_format((float) $unit->rent_amount, 2) }}</div>
                </div>
            </div>

            @if($unit->parent)
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('units.parent_unit') }}</label>
                <div class="col-lg-8">
                    <div class="fw-bold">{{ $unit->parent->name }}</div>
                </div>
            </div>
            @endif

            <div class="row mb-6">
                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('messages.created_at') ?? 'Created At' }}</label>
                <div class="col-lg-8">
                    <div class="text-muted">
                        {{ optional($unit->created_at)->format('Y-m-d H:i') }}
                        <span class="mx-2">•</span>
                        <span>{{ optional($unit->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('messages.updated_at') ?? 'Updated At' }}</label>
                <div class="col-lg-8">
                    <div class="text-muted">
                        {{ optional($unit->updated_at)->format('Y-m-d H:i') }}
                        <span class="mx-2">•</span>
                        <span>{{ optional($unit->updated_at)->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
