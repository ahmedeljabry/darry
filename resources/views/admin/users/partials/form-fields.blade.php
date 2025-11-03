@php
    $isEdit = ($mode ?? 'create') === 'edit';
    $userModel = $user ?? null;
    $properties = $properties ?? [];
    $roles = $roles ?? [];
    $statusOptions = $statusOptions ?? [];

    $currentRole = $userModel ? $userModel->roles->pluck('name')->first() : null;

    $propertyValue = old('property_id', optional($userModel)->property_id);
    if (! $canManageSystem ?? false) {
        $propertyValue = old('property_id', $currentPropertyId ?? null);
    }

    $nameValue = old('name', optional($userModel)->name);
    $phoneValue = old('phone', optional($userModel)->phone);
    $emailValue = old('email', optional($userModel)->email);
    $roleValue = old('role', $currentRole);
    $statusValue = old('status', optional($userModel)->status);

    $passwordHelp = $isEdit ? __('users.password_help_edit') : __('users.password_help_create');
@endphp

<div class="d-flex flex-column">
    <div class="bg-light rounded-3 p-6 border border-dashed mb-8">
        <div class="d-flex align-items-center mb-5">
            <span class="symbol symbol-45px symbol-light-primary me-4">
                <span class="symbol-label">
                    <i class="la la-building fs-2 text-primary"></i>
                </span>
            </span>
            <div>
                <h3 class="fs-5 fw-bold mb-1">{{ __('users.sections.property.title') }}</h3>
                <p class="text-muted mb-0">{{ __('users.sections.property.subtitle') }}</p>
            </div>
        </div>

        @if($canManageSystem ?? false)
            <div class="row">
                <div class="col-12 col-lg-8">
                    <x-admin.select-solid
                        name="property_id"
                        :label="__('users.assign_property')"
                        :options="$properties"
                        :value="$propertyValue"
                        :help="__('users.sections.property.help')"
                    />
                </div>
            </div>
        @else
            <input type="hidden" name="property_id" value="{{ old('property_id', $currentPropertyId ?? null) }}">
            <div class="alert alert-light-warning d-flex align-items-start gap-3 mb-6">
                <i class="la la-lock fs-2 text-warning"></i>
                <div>
                    <span class="fw-semibold d-block">{{ __('users.sections.property.locked_warning') }}</span>
                    <span class="text-muted small">{{ __('users.sections.property.locked_hint') }}</span>
                </div>
            </div>
            <x-admin.input-solid
                name="property_display"
                :label="__('users.assign_property')"
                :value="$currentPropertyName ?? ''"
                disabled
            />
        @endif
    </div>

    <div class="bg-white rounded-3 p-6 border border-dashed mb-8">
        <div class="d-flex align-items-center mb-5">
            <span class="symbol symbol-45px symbol-light-success me-4">
                <span class="symbol-label">
                    <i class="la la-id-card fs-2 text-success"></i>
                </span>
            </span>
            <div>
                <h3 class="fs-5 fw-bold mb-1">{{ __('users.sections.profile.title') }}</h3>
                <p class="text-muted mb-0">{{ __('users.sections.profile.subtitle') }}</p>
            </div>
        </div>
        <div class="row g-5">
            <div class="col-12 col-xl-6">
                <x-admin.input-solid
                    name="name"
                    :label="__('tenants.full_name')"
                    :value="$nameValue"
                    :placeholder="__('tenants.full_name')"
                    required
                />
            </div>
            <div class="col-12 col-xl-6">
                <x-admin.input-solid
                    name="phone"
                    :label="__('tenants.phone')"
                    :value="$phoneValue"
                    placeholder="05XXXXXXXX"
                />
            </div>
            <div class="col-12 col-xl-6">
                <x-admin.input-solid
                    name="email"
                    type="email"
                    :label="__('auth.email')"
                    :value="$emailValue"
                    placeholder="name@example.com"
                    required
                />
            </div>
        </div>
    </div>

    <div class="bg-light rounded-3 p-6 border border-dashed mb-8">
        <div class="d-flex align-items-center mb-5">
            <span class="symbol symbol-45px symbol-light-warning me-4">
                <span class="symbol-label">
                    <i class="la la-lock fs-2 text-warning"></i>
                </span>
            </span>
            <div>
                <h3 class="fs-5 fw-bold mb-1">{{ __('users.sections.security.title') }}</h3>
                <p class="text-muted mb-0">{{ __('users.sections.security.subtitle') }}</p>
            </div>
        </div>
        <div class="row g-5">
            <div class="col-12 col-xl-6">
                <x-admin.input-solid
                    name="password"
                    type="password"
                    :label="__('auth.password')"
                    :required="!$isEdit"
                    :help="$passwordHelp"
                />
            </div>
            <div class="col-12 col-xl-6">
                <x-admin.input-solid
                    name="password_confirmation"
                    type="password"
                    :label="__('auth.password_confirmation')"
                />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3 p-6 border border-dashed">
        <div class="d-flex align-items-center mb-5">
            <span class="symbol symbol-45px symbol-light-info me-4">
                <span class="symbol-label">
                    <i class="la la-user-shield fs-2 text-info"></i>
                </span>
            </span>
            <div>
                <h3 class="fs-5 fw-bold mb-1">{{ __('users.sections.role.title') }}</h3>
                <p class="text-muted mb-0">{{ __('users.sections.role.subtitle') }}</p>
            </div>
        </div>
        <div class="row g-5">
            <div class="col-12 col-xl-6">
                <x-admin.select-solid
                    name="role"
                    :label="__('roles.roles') ?? 'الدور'"
                    :options="$roles"
                    :value="$roleValue"
                    :help="__('users.sections.role.role_help')"
                    required
                />
            </div>
            <div class="col-12 col-xl-6">
                <x-admin.select-solid
                    name="status"
                    :label="__('owners.status')"
                    :options="$statusOptions"
                    :value="$statusValue"
                    :help="__('users.sections.role.status_help')"
                    required
                />
            </div>
        </div>
    </div>
</div>
