@php
    $properties = $properties ?? [];
    $canManageSystem = $canManageSystem ?? false;
    $currentPropertyId = $currentPropertyId ?? null;
    $currentPropertyName = $currentPropertyName ?? null;
    $value = old('property_id', $value ?? ($canManageSystem ? null : $currentPropertyId));
@endphp

<div class="bg-light rounded-3 p-6 border border-dashed mb-8">
    <div class="d-flex align-items-center mb-5">
        <span class="symbol symbol-45px symbol-light-primary me-4">
            <span class="symbol-label">
                <i class="la la-building fs-2 text-primary"></i>
            </span>
        </span>
        <div>
            <h3 class="fs-5 fw-bold mb-1">{{ __('tenants.sections.property.title') }}</h3>
            <p class="text-muted mb-0">{{ __('tenants.sections.property.subtitle') }}</p>
        </div>
    </div>

    @if($canManageSystem)
        <div class="row">
            <div class="col-12 col-lg-8">
                <x-admin.select-solid
                    name="property_id"
                    :label="__('tenants.assign_property')"
                    :options="$properties"
                    :value="$value"
                    :help="__('tenants.sections.property.help')"
                    required
                />
            </div>
        </div>
    @else
        <input type="hidden" name="property_id" value="{{ old('property_id', $currentPropertyId) }}">
        <div class="alert alert-light-warning d-flex align-items-start gap-3 mb-6">
            <i class="la la-lock fs-2 text-warning"></i>
            <div>
                <span class="fw-semibold d-block">{{ __('tenants.sections.property.locked_warning') }}</span>
                <span class="text-muted small">{{ __('tenants.sections.property.locked_hint') }}</span>
            </div>
        </div>
        <x-admin.input-solid
            name="property_display"
            :label="__('tenants.assign_property')"
            :value="$currentPropertyName ?? ''"
            disabled
        />
    @endif
</div>
