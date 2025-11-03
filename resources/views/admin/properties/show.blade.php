@extends('admin.layouts.master')
@section('title', $property->name)

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('properties.title') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('admin.properties.index') }}" class="text-muted">{{ __('properties.list') }}</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <span class="text-muted">{{ $property->name }}</span>
        </li>
    </ul>
@endsection

@section('content')
    @php
        $thumbnailUrl = $property->thumbnail ? \Illuminate\Support\Facades\Storage::url($property->thumbnail) : null;
        $imageUrls = collect($property->images ?? [])->map(fn ($path) => \Illuminate\Support\Facades\Storage::url($path));
        $useTypeValue = $property->use_type?->value ?? $property->use_type;
        $rentTypeOptions = collect($rentTypes)->mapWithKeys(fn ($type) => [$type->value => __('units.rent_types.' . $type->value)])->toArray();
        $statusOptions = collect($unitStatuses)->mapWithKeys(fn ($status) => [$status->value => __('units.statuses.' . $status->value)])->toArray();
    @endphp

    <div class="row">
        <div class="col-xl-8">
            <div class="card card-custom mb-5">
                <div class="card-header border-0">
                    <div class="card-title">
                        <h3 class="card-label mb-3">{{ $property->name }}</h3>
                        <div class="text-muted">
                            {{ $property->full_address ?: __('properties.address') . ': --' }}
                        </div>
                    </div>
                </div>
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('properties.use_type') }}</label>
                        <div class="col-lg-8">
                            <div class="fw-bold">
                                {{ $useTypeValue ? __('properties.use_types.' . $useTypeValue) : '--' }}
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('properties.area_sqm') }}</label>
                        <div class="col-lg-8">
                            <div class="fw-bold">{{ $property->area_sqm ?: '--' }}</div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('properties.coordinates') }}</label>
                        <div class="col-lg-8">
                            <div class="fw-bold ltr">{{ $property->coordinates ?: '--' }}</div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('properties.facilities') }}</label>
                        <div class="col-lg-8">
                            @if($property->facilities->isEmpty())
                                <span class="text-muted">{{ __('messages.not_available') ?? 'لا يوجد بيانات' }}</span>
                            @else
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($property->facilities as $facility)
                                        <span class="badge badge-light-primary fw-bold px-3 py-2">{{ $facility->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-custom mb-5">
                <div class="card-header border-0">
                    <div class="card-title">
                        <h3 class="card-label">{{ __('properties.images') }}</h3>
                    </div>
                </div>
                <div class="card-body border-top p-9">
                    @if(!$thumbnailUrl && $imageUrls->isEmpty())
                        <span class="text-muted">{{ __('messages.not_available') ?? 'لا توجد صور' }}</span>
                    @else
                        <div class="d-flex flex-wrap gap-4">
                            @if($thumbnailUrl)
                                <div class="text-center">
                                    <div class="fw-bold mb-2 text-muted">{{ __('properties.thumbnail') }}</div>
                                    <img src="{{ $thumbnailUrl }}" alt="{{ $property->name }}" class="rounded border" style="width: 180px; height: 140px; object-fit: cover;">
                                </div>
                            @endif
                            @foreach($imageUrls as $url)
                                <img src="{{ $url }}" alt="{{ $property->name }}" class="rounded border" style="width: 150px; height: 120px; object-fit: cover;">
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="card card-custom mb-5">
                <div class="card-header border-0">
                    <div class="card-title">
                        <h3 class="card-label">{{ __('floors.title') }}</h3>
                    </div>
                </div>
                <div class="card-body border-top p-9">
                    @if($property->floors->isEmpty())
                        <span class="text-muted">{{ __('floors.empty') }}</span>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle">
                                <thead>
                                    <tr>
                                        <th>{{ __('floors.name_ar') }}</th>
                                        <th>{{ __('floors.description_ar') }}</th>
                                        <th>{{ __('floors.description_en') }}</th>
                                        <th class="text-end">{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($property->floors as $floor)
                                        <tr>
                                            <td class="fw-semibold">{{ $floor->name_ar }}</td>
                                            <td class="text-muted">{{ $floor->description_ar ?: '--' }}</td>
                                            <td class="text-muted">{{ $floor->description_en ?: '--' }}</td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <button class="btn btn-sm btn-light-primary" data-toggle="modal" data-target="#editFloorModal{{ $floor->id }}">
                                                        {{ __('messages.edit') }}
                                                    </button>
                                                    <form action="{{ route('admin.properties.floors.destroy', [$property, $floor]) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-light-danger">{{ __('messages.delete') }}</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="editFloorModal{{ $floor->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('floors.edit') }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <form action="{{ route('admin.properties.floors.update', [$property, $floor]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <x-admin.input-solid name="name_ar" :value="$floor->name_ar" :label="__('floors.name_ar')" />
                                                            <x-admin.input-solid name="description_ar" :value="$floor->description_ar" :label="__('floors.description_ar')" />
                                                            <x-admin.input-solid name="description_en" :value="$floor->description_en" :label="__('floors.description_en')" />
                                                            <x-admin.input-solid name="sort_order" type="number" :value="$floor->sort_order" :label="__('floors.sort_order')" min="0" required />
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('messages.cancel') }}</button>
                                                            <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card card-custom">
                <div class="card-header border-0">
                    <div class="card-title">
                        <h3 class="card-label">{{ __('units.title') }}</h3>
                    </div>
                </div>
                <div class="card-body border-top p-0">
                    @if($property->units->isEmpty())
                        <div class="p-9 text-muted">{{ __('units.empty') ?? 'لا توجد وحدات بعد' }}</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('units.name') }}</th>
                                        <th>{{ __('units.unit_type') }}</th>
                                        <th>{{ __('units.rent_amount') }}</th>
                                        <th>{{ __('units.status') }}</th>
                                        <th class="text-end">{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($property->units as $unit)
                                        @php
                                            $typeVal = $unit->unit_type?->value ?? $unit->unit_type;
                                            $statusVal = $unit->status?->value ?? $unit->status;
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $unit->name }}</td>
                                            <td>{{ $typeVal ? __('units.types.' . $typeVal) : '--' }}</td>
                                            <td>{{ number_format((float) $unit->rent_amount, 2) }}</td>
                                            <td>{{ $statusVal ? __('units.statuses.' . $statusVal) : '--' }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.units.show', $unit) }}" class="btn btn-sm btn-light">{{ __('messages.show') }}</a>
                                                <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-sm btn-light-primary">{{ __('messages.edit') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card card-custom mb-5">
                <div class="card-header border-0">
                    <div class="card-title">
                        <h3 class="card-label">{{ __('units.quick_add') }}</h3>
                    </div>
                </div>
                <form action="{{ route('admin.units.store') }}" method="POST" class="form">
                    @csrf
                    <div class="card-body border-top p-6">
                        <input type="hidden" name="property_id" value="{{ $property->id }}">

                        <x-admin.input-solid name="name" :label="__('units.name')" required />

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-bold">{{ __('units.unit_type') }}</label>
                            <div class="col-lg-8">
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach($unitTypes as $type)
                                        <label class="btn btn-outline-primary btn-sm slot-pill">
                                            <input type="radio" name="unit_type" value="{{ $type->value }}" required>
                                            {{ __('units.types.' . $type->value) }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <x-admin.input-solid name="capacity" type="number" :label="__('units.capacity')" min="0" />

                        <x-admin.select-solid name="rent_type" :label="__('units.rent_type')" :options="$rentTypeOptions" required />

                        <x-admin.input-solid name="rent_amount" type="number" step="0.01" min="0" :label="__('units.rent_amount')" required />

                        <x-admin.select-solid name="status" :label="__('units.status')" :options="$statusOptions" required />

                        <x-admin.select-solid name="parent_id" :label="__('units.parent_unit')" :options="$parentUnits" />
                    </div>
                    <div class="card-footer border-0 pt-0 pb-6 px-6">
                        <button type="submit" class="btn btn-primary w-100">{{ __('messages.save') }}</button>
                    </div>
                </form>
            </div>

            <div class="card card-custom">
                <div class="card-header border-0">
                    <div class="card-title">
                        <h3 class="card-label">{{ __('floors.add') }}</h3>
                    </div>
                </div>
                <form action="{{ route('admin.properties.floors.store', $property) }}" method="POST" class="form">
                    @csrf
                    <div class="card-body border-top p-6">
                        <x-admin.input-solid name="name_ar" :label="__('floors.name_ar')" />
                        <x-admin.input-solid name="description_ar" :label="__('floors.description_ar')" />
                        <x-admin.input-solid name="description_en" :label="__('floors.description_en')" />
                        <x-admin.input-solid name="sort_order" type="number" :label="__('floors.sort_order')" min="0" required />
                    </div>
                    <div class="card-footer border-0 pt-0 pb-6 px-6">
                        <button type="submit" class="btn btn-primary w-100">{{ __('messages.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
