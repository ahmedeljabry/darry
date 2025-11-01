@extends('admin.layouts.master')

@section('breadcrumb', __('units.create'))

@section('content')
    <x-admin.form-card :title="__('units.create')" :action="route('admin.units.store')" :back="route('admin.units.index')">
        <x-admin.select-solid name="property_id" :label="__('units.property')" :options="$properties" required />
        <x-admin.input-solid name="name" :label="__('units.name')" placeholder="{{ __('units.name') }}" required />
        <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">
                {{ __('units.unit_type') }}
            </label>
            <div class="col-lg-8">
                @php $typeOld = old('unit_type'); @endphp
                <div class="d-flex flex-wrap gap-4" id="unitTypeGroup">
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
        <x-admin.input-solid name="capacity" type="number" :label="__('units.capacity')" placeholder="مثلاً: 4" min="1" />
        <x-admin.select-solid name="rent_type" :label="__('units.rent_type')"
            :options="['DAILY' => __('units.rent_types.DAILY'), 'MONTHLY' => __('units.rent_types.MONTHLY'), 'DAILY_OR_MONTHLY' => __('units.rent_types.DAILY_OR_MONTHLY')]"
            placeholder="{{ __('units.rent_type') }}" required />
        <x-admin.money-solid name="rent_amount" :label="__('units.rent_amount')" placeholder="مثلاً: 1500" required />
        <x-admin.select-solid name="status" :label="__('units.status')" :options="['ACTIVE' => __('units.statuses.ACTIVE'), 'INACTIVE' => __('units.statuses.INACTIVE')]" placeholder="{{ __('units.status') }}" required />
    </x-admin.form-card>
@endsection
