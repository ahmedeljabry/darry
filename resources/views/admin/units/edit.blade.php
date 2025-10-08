@extends('admin.layouts.master')
@section('title', __('units.edit'))

@section('breadcrumbs')
    <!--begin::Page Title-->
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('units.edit') }}</h5>
    <!--end::Page Title-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:void();" class="text-muted">{{ __('units.title') }}</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:void();" class="text-muted">{{ __('units.edit') }}</a>
        </li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('content')
    <x-admin.form-card :title="__('units.edit')" :action="route('admin.units.update', $unit)" method="PUT" :back="route('admin.units.index')">
        <x-admin.input-solid name="name" :label="__('units.name')" :value="$unit->name" placeholder="{{ __('units.name') }}" required />
        <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">
                {{ __('units.unit_type') }}
            </label>
            <div class="col-lg-8">
                @php $typeOld = old('unit_type', $unit->unit_type->value ?? $unit->unit_type); @endphp
                <div class="d-flex flex-wrap gap-2" id="unitTypeGroup">
                    @foreach(['APARTMENT','ROOM','BED'] as $val)
                        <label class="btn btn-outline-primary btn-sm slot-pill">
                            <input type="radio" name="unit_type" value="{{ $val }}" {{ $typeOld === $val ? 'checked' : '' }} required>
                            @if($val==='APARTMENT') <i class="la la-building me-1"></i> @endif
                            @if($val==='ROOM')     <i class="la la-door-open me-1"></i> @endif
                            @if($val==='BED')      <i class="la la-bed me-1"></i> @endif
                            {{ __('units.types.'.$val) }}
                        </label>
                    @endforeach
                </div>
                @error('unit_type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
        </div>
        <x-admin.input-solid name="capacity" type="number" :label="__('units.capacity')" :value="$unit->capacity" placeholder="مثلاً: 4" min="0" required />
        <x-admin.select-solid name="rent_type" :label="__('units.rent_type')"
            :options="['DAILY' => 'يومي', 'MONTHLY' => 'شهري', 'DAILY_OR_MONTHLY' => 'يومي أو شهري']"
            :value="$unit->rent_type->value ?? $unit->rent_type" placeholder="{{ __('units.rent_type') }}" required />
        <x-admin.money-solid name="rent_amount" :label="__('units.rent_amount')" :value="$unit->rent_amount" placeholder="مثلاً: 1500" required />
        <x-admin.select-solid name="status" :label="__('units.status')" :options="['ACTIVE' => 'نشطة', 'INACTIVE' => 'غير نشطة']"
            :value="$unit->status->value ?? $unit->status" placeholder="{{ __('units.status') }}" required />
    </x-admin.form-card>
@endsection
