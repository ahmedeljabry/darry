@extends('admin.layouts.master')

@section('title', __('menu.system_settings'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('menu.system_settings') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('menu.dashboard') }}</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <span class="text-muted">{{ __('menu.settings') }}</span>
        </li>
        <li class="breadcrumb-item text-muted">{{ __('menu.system_settings') }}</li>
    </ul>
@endsection

@section('content')
    @php
        $navigation = [
            [
                'id' => 'general-settings',
                'icon' => 'la la-cog',
                'title' => __('settings.general_settings'),
                'description' => __('settings.general_settings_hint'),
            ],
            [
                'id' => 'branding-settings',
                'icon' => 'la la-image',
                'title' => __('settings.branding_settings'),
                'description' => __('settings.branding_settings_hint'),
            ],
            [
                'id' => 'email-settings',
                'icon' => 'la la-envelope',
                'title' => __('settings.email_settings'),
                'description' => __('settings.email_settings_hint'),
            ],
        ];

        $mailDriversForSelect = collect($mailDriverOptions ?? [])->mapWithKeys(
            fn ($label, $value) => [$value => __('settings.mail_driver_' . $value)]
        )->toArray();
        $mailEncryptionForSelect = collect($mailEncryptionOptions ?? [])->mapWithKeys(
            fn ($label, $value) => [$value => __('settings.mail_encryption_' . ($value === '' ? 'none' : $value))]
        )->toArray();
    @endphp

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-xl-4 mb-10 mb-xl-0">
                <div class="card shadow-sm mb-6">
                    <div class="card-body p-6">
                        <h4 class="font-weight-bolder text-dark mb-4">
                            {{ __('settings.system_overview') }}
                        </h4>
                        <p class="text-muted mb-6">{{ __('settings.system_overview_hint') }}</p>
                        <div class="settings-summary__item">
                            <span class="text-muted font-weight-bold">{{ __('layout.app_name') }}</span>
                            <span class="text-dark font-weight-bolder d-block mt-1">
                                {{ $generalSummary['app_name'] ?: __('settings.not_defined') }}
                            </span>
                        </div>
                        <div class="settings-summary__item">
                            <span class="text-muted font-weight-bold">{{ __('settings.primary_email') }}</span>
                            <span class="d-block mt-1">
                                <a href="mailto:{{ $generalSummary['company_email'] }}" class="text-dark font-weight-bolder text-hover-primary">
                                    {{ $generalSummary['company_email'] ?: __('settings.not_defined') }}
                                </a>
                            </span>
                        </div>
                        <div class="settings-summary__item">
                            <span class="text-muted font-weight-bold">{{ __('settings.primary_phone') }}</span>
                            <span class="text-dark font-weight-bolder d-block mt-1">
                                {{ $generalSummary['company_phone'] ?: __('settings.not_defined') }}
                            </span>
                        </div>
                        <div class="settings-summary__item mb-0">
                            <span class="text-muted font-weight-bold">{{ __('settings.primary_address') }}</span>
                            <span class="text-dark font-weight-bolder d-block mt-1">
                                {{ $generalSummary['address'] ?: __('settings.not_defined') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-6">
                    <div class="card-body p-6">
                        <h4 class="font-weight-bolder text-dark mb-4">
                            {{ __('settings.quick_status') }}
                        </h4>
                        <div class="settings-status d-flex align-items-center mb-4">
                            <span class="symbol symbol-45 symbol-light-primary mr-4">
                                <span class="symbol-label">
                                    <i class="la la-palette text-primary font-size-h4"></i>
                                </span>
                            </span>
                            <div>
                                <div class="text-dark font-weight-bolder">
                                    {{ __('settings.summary_branding_title') }}
                                </div>
                                <div class="text-muted font-size-sm">
                                    {{ $brandingConfigured ? __('settings.branding_ready') : __('settings.branding_missing') }}
                                </div>
                            </div>
                        </div>
                        <div class="settings-status d-flex align-items-center">
                            <span class="symbol symbol-45 symbol-light-success mr-4">
                                <span class="symbol-label">
                                    <i class="la la-paper-plane text-success font-size-h4"></i>
                                </span>
                            </span>
                            <div>
                                <div class="text-dark font-weight-bolder">
                                    {{ __('settings.summary_email_title') }}
                                </div>
                                <div class="text-muted font-size-sm">
                                    {{ $mailConfigured ? __('settings.mail_ready') : __('settings.mail_missing') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm sticky-card">
                    <div class="card-body p-5">
                        <h5 class="font-weight-bolder text-dark mb-4">{{ __('settings.quick_navigation') }}</h5>
                        <div class="settings-nav list-group">
                            @foreach($navigation as $item)
                                <a href="#{{ $item['id'] }}" data-target="{{ $item['id'] }}"
                                   class="settings-nav__link list-group-item list-group-item-action d-flex align-items-start">
                                    <span class="settings-nav__icon mr-3">
                                        <i class="{{ $item['icon'] }}"></i>
                                    </span>
                                    <span>
                                        <span class="d-block font-weight-bolder text-dark">{{ $item['title'] }}</span>
                                        <span class="text-muted font-size-sm d-block">{{ $item['description'] }}</span>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                {{-- General --}}
                <div class="card shadow-sm mb-9" id="general-settings" data-section="general-settings">
                    <div class="card-header border-0 py-5">
                        <div>
                            <h3 class="card-title font-weight-bolder text-dark mb-2">
                                {{ __('settings.general_settings') }}
                            </h3>
                            <span class="text-muted font-size-sm">
                                {{ __('settings.general_settings_hint') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.input-solid
                                    name="app_name"
                                    :label="__('layout.app_name')"
                                    :value="$values['app_name']"
                                    required
                                    :help="__('layout.app_name_help') ?? ''"
                                />
                            </div>
                            <div class="col-md-6">
                                <x-admin.input-solid
                                    name="company_email"
                                    :label="__('settings.primary_email')"
                                    :value="$values['company_email']"
                                    type="email"
                                    :help="__('settings.used_for_notifications')"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.input-solid
                                    name="company_phone"
                                    :label="__('settings.primary_phone')"
                                    :value="$values['company_phone']"
                                />
                            </div>
                            <div class="col-md-6">
                                <x-admin.input-solid
                                    name="address"
                                    :label="__('settings.primary_address')"
                                    :value="$values['address']"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-admin.select-solid
                                    name="locale"
                                    :label="__('layout.locale')"
                                    :options="$localeOptions"
                                    :placeholder="__('settings.locale_placeholder')"
                                    :value="$values['locale']"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-admin.select-solid
                                    name="currency"
                                    :label="__('layout.currency')"
                                    :options="$currencyOptions"
                                    :placeholder="__('settings.currency_placeholder')"
                                    :value="$values['currency']"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-admin.select-solid
                                    name="timezone"
                                    :label="__('layout.timezone')"
                                    :options="$timezoneOptions"
                                    :placeholder="__('settings.timezone_placeholder')"
                                    :value="$values['timezone']"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Branding --}}
                <div class="card shadow-sm mb-9" id="branding-settings" data-section="branding-settings">
                    <div class="card-header border-0 py-5">
                        <div>
                            <h3 class="card-title font-weight-bolder text-dark mb-2">
                                {{ __('settings.branding_settings') }}
                            </h3>
                            <span class="text-muted font-size-sm">
                                {{ __('settings.branding_settings_hint') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.dropzone
                                    name="logo"
                                    :label="__('layout.logo')"
                                    accept="image/*"
                                    :existing="$values['logo'] ?? null"
                                    :existing-urls="$values['logo_url'] ?? null"
                                />
                                <small class="form-text text-muted mt-2">
                                    {{ __('settings.logo_hint') }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <x-admin.dropzone
                                    name="favicon"
                                    :label="__('layout.favicon')"
                                    accept="image/*"
                                    :existing="$values['favicon'] ?? null"
                                    :existing-urls="$values['favicon_url'] ?? null"
                                />
                                <small class="form-text text-muted mt-2">
                                    {{ __('settings.favicon_hint') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Email --}}
                <div class="card shadow-sm mb-9" id="email-settings" data-section="email-settings">
                    <div class="card-header border-0 py-5">
                        <div>
                            <h3 class="card-title font-weight-bolder text-dark mb-2">
                                {{ __('settings.email_settings') }}
                            </h3>
                            <span class="text-muted font-size-sm">
                                {{ __('settings.email_settings_hint') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="alert alert-secondary d-flex align-items-center" role="alert">
                            <i class="la la-info-circle icon-lg mr-3"></i>
                            <div>{{ __('settings.email_hint') }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <x-admin.input-solid
                                    name="mail_from_address"
                                    :label="__('settings.sender_email')"
                                    :value="$values['mail_from_address']"
                                    type="email"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-admin.input-solid
                                    name="mail_host"
                                    :label="__('settings.smtp_host')"
                                    :value="$values['mail_host']"
                                    placeholder="smtp.example.com"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-admin.input-solid
                                    name="mail_username"
                                    :label="__('settings.smtp_username')"
                                    :value="$values['mail_username']"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <x-admin.select-solid
                                    name="mail_driver"
                                    :label="__('settings.smtp_driver')"
                                    :options="$mailDriversForSelect"
                                    :value="$values['mail_driver']"
                                />
                            </div>
                            <div class="col-md-3">
                                <x-admin.input-solid
                                    name="mail_password"
                                    type="password"
                                    :label="__('settings.smtp_password')"
                                    :value="$values['mail_password']"
                                    autocomplete="new-password"
                                />
                            </div>
                            <div class="col-md-3">
                                <x-admin.select-solid
                                    name="mail_encryption"
                                    :label="__('settings.smtp_encryption')"
                                    :options="$mailEncryptionForSelect"
                                    :value="$values['mail_encryption']"
                                />
                            </div>
                            <div class="col-md-3">
                                <x-admin.input-solid
                                    name="mail_port"
                                    type="number"
                                    :label="__('settings.smtp_port')"
                                    :value="$values['mail_port']"
                                    placeholder="587"
                                />
                            </div>
                        </div>

                        @if($smtpTestRoute)
                            <div class="d-flex align-items-center mt-4">
                                <button type="button"
                                        id="test-smtp"
                                        data-url="{{ $smtpTestRoute }}"
                                        class="btn btn-light-primary px-6">
                                    <i class="la la-paper-plane ml-2"></i>{{ __('settings.test_smtp') }}
                                </button>
                                <small class="text-muted ml-4">{{ __('settings.test_smtp_hint') }}</small>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted">
                            <i class="la la-shield-alt mr-1"></i>{{ __('layout.save_notice') ?? __('messages.save') }}
                        </div>
                        <button type="submit" class="btn btn-primary px-8">
                            <i class="la la-save ml-2"></i>{{ __('messages.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('styles')
    <style>
        .settings-summary__item {
            margin-bottom: 1.25rem;
        }
        .settings-summary__item:last-child {
            margin-bottom: 0;
        }
        .settings-status + .settings-status {
            margin-top: 1.5rem;
        }
        .settings-nav__link {
            border: 0;
            border-radius: 0.85rem;
            padding: 1rem 1.25rem;
            transition: all 0.2s ease;
            margin-bottom: 0.75rem;
        }
        .settings-nav__icon {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(96, 110, 234, 0.08);
            border-radius: 0.75rem;
            color: #606EEA;
            font-size: 1.2rem;
        }
        .settings-nav__link:hover,
        .settings-nav__link.settings-nav__link--active {
            background: rgba(96, 110, 234, 0.1);
        }
        .settings-nav__link.settings-nav__link--active .settings-nav__icon {
            background: rgba(96, 110, 234, 0.2);
        }
        .sticky-card {
            position: sticky;
            top: 90px;
        }
        @media (max-width: 991px) {
            .sticky-card {
                position: static;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function () {
            const navLinks = document.querySelectorAll('.settings-nav__link');
            const sections = document.querySelectorAll('[data-section]');
            const activeClass = 'settings-nav__link--active';

            navLinks.forEach(link => {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    const target = document.getElementById(this.dataset.target);
                    if (target) {
                        window.scrollTo({
                            top: target.offsetTop - 90,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const id = entry.target.getAttribute('id');
                            navLinks.forEach(link => {
                                link.classList.toggle(activeClass, link.dataset.target === id);
                            });
                        }
                    });
                }, {rootMargin: '-45% 0px -45% 0px'});

                sections.forEach(section => observer.observe(section));
            } else {
                navLinks[0]?.classList.add(activeClass);
            }

            const smtpButton = document.getElementById('test-smtp');
            if (smtpButton) {
                const url = smtpButton.dataset.url;
                if (!url) {
                    smtpButton.disabled = true;
                    return;
                }
                smtpButton.addEventListener('click', function () {
                    smtpButton.disabled = true;
                    smtpButton.classList.add('spinner');
                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            window.toastr?.[data.ok ? 'success' : 'error'](data.message || (data.ok ? 'SMTP works!' : 'SMTP failed'));
                        })
                        .catch(() => {
                            window.toastr?.error('Request failed');
                        })
                        .finally(() => {
                            smtpButton.classList.remove('spinner');
                            smtpButton.disabled = false;
                        });
                });
            }
        })();
    </script>
@endpush

