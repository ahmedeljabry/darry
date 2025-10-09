@extends('admin.layouts.master')
@section('title', __('menu.system_settings'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('menu.system_settings') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('menu.dashboard') }}</a></li>
        <li class="breadcrumb-item text-muted"><a href="#" class="text-muted">{{ __('menu.settings') }}</a></li>
        <li class="breadcrumb-item text-muted">{{ __('menu.system_settings') }}</li>
    </ul>
@endsection

@section('content')
    <x-admin.form-card :title="__('menu.system_settings')" :action="route('admin.settings.update')" method="POST" :back="url()->previous()" enctype="multipart/form-data">
        @csrf

        {{-- Tabs --}}
        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">
                    <i class="la la-cog"></i> {{ __('layout.general') ?? 'عام' }}
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="branding-tab" data-toggle="tab" href="#branding" role="tab" aria-controls="branding" aria-selected="false">
                    <i class="la la-image"></i> {{ __('layout.branding') ?? 'الهوية' }}
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="email-tab" data-toggle="tab" href="#email" role="tab" aria-controls="email" aria-selected="false">
                    <i class="la la-envelope"></i> {{ __('settings.email_settings') }}
                </a>
            </li>
        </ul>

        <div class="tab-content border-left border-right border-bottom p-4" id="settingsTabsContent">
            {{-- General --}}
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="row">
                    <div class="col-md-6">
                        <x-admin.input-solid name="app_name" :label="__('layout.app_name') ?? 'اسم التطبيق'" :value="$values['app_name']" required help="{{ __('layout.app_name_help') ?? 'سيظهر هذا الاسم في العناوين ورسائل البريد.' }}" />
                    </div>
                    <div class="col-md-6">
                        <x-admin.input-solid name="company_email" :label="__('auth.email')" :value="$values['company_email']" type="email" help="{{ __('settings.used_for_notifications') ?? 'يُستخدم لإشعارات النظام والفواتير.' }}" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <x-admin.input-solid name="company_phone" :label="__('tenants.phone')" :value="$values['company_phone']" />
                    </div>
                    <div class="col-md-6">
                        <x-admin.input-solid name="address" :label="__('tenants.address') ?? 'العنوان'" :value="$values['address']" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <x-admin.input-solid name="locale" :label="__('layout.locale') ?? 'اللغة'" :value="$values['locale']" placeholder="ar|en" />
                    </div>
                    <div class="col-md-4">
                        <x-admin.input-solid name="currency" :label="__('layout.currency') ?? 'العملة'" :value="$values['currency']" placeholder="EGP|USD" />
                    </div>
                    <div class="col-md-4">
                        <x-admin.select-solid name="timezone" :label="__('layout.timezone') ?? 'المنطقة الزمنية'"
                            :options="array_combine(timezone_identifiers_list(), timezone_identifiers_list())"
                            :value="$values['timezone'] ?? config('app.timezone')" />
                    </div>
                </div>
            </div>

            {{-- Branding --}}
            <div class="tab-pane fade" id="branding" role="tabpanel" aria-labelledby="branding-tab">
                <div class="row">
                    <div class="col-md-6">
                        <x-admin.dropzone name="logo" :label="__('layout.logo') ?? 'الشعار'" accept="image/*" />
                        <small class="form-text text-muted">PNG/SVG, {{ __('layout.recommended') ?? 'مستحسن' }}: 240×60</small>
                        @if(!empty($values['logo_url']))
                            <div class="mt-3">
                                <span class="d-block mb-2">{{ __('layout.current_logo') ?? 'الشعار الحالي' }}:</span>
                                <img src="{{ $values['logo_url'] }}" alt="logo" class="img-fluid border rounded p-2 bg-white" style="max-height:60px">
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <x-admin.dropzone name="favicon" :label="__('layout.favicon') ?? 'الأيقونة'" accept="image/*" />
                        <small class="form-text text-muted">ICO/PNG 32×32</small>
                        @if(!empty($values['favicon_url']))
                            <div class="mt-3">
                                <span class="d-block mb-2">{{ __('layout.current_favicon') ?? 'الأيقونة الحالية' }}:</span>
                                <img src="{{ $values['favicon_url'] }}" alt="favicon" class="border rounded p-1 bg-white" style="width:32px;height:32px">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Email --}}
            <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
                <div class="alert alert-secondary d-flex align-items-center" role="alert">
                    <i class="la la-info-circle mr-2"></i>
                    <div>{{ __('settings.email_hint') ?? 'اضبط مزود البريد لإرسال الإشعارات والفواتير والروابط.' }}</div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <x-admin.input-solid name="mail_from_address" :label="__('settings.sender_email')" :value="$values['mail_from_address']" type="email" />
                    </div>
                    <div class="col-md-4">
                        <x-admin.input-solid name="mail_host" :label="__('settings.smtp_host')" :value="$values['mail_host']" placeholder="smtp.example.com" />
                    </div>
                    <div class="col-md-4">
                        <x-admin.input-solid name="mail_username" :label="__('settings.smtp_username')" :value="$values['mail_username']" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <x-admin.select-solid name="mail_driver" :label="__('settings.smtp_driver')" :options="['smtp' => 'SMTP', 'log' => 'Log', 'sendmail' => 'Sendmail']" :value="$values['mail_driver']" />
                    </div>
                    <div class="col-md-3">
                        <x-admin.input-solid name="mail_password" type="password" :label="__('settings.smtp_password')" :value="$values['mail_password']" autocomplete="new-password" />
                    </div>
                    <div class="col-md-3">
                        <x-admin.select-solid name="mail_encryption" :label="__('settings.smtp_encryption')" :options="['' => 'None', 'tls' => 'TLS', 'ssl' => 'SSL']" :value="$values['mail_encryption']" />
                    </div>
                    <div class="col-md-3">
                        <x-admin.input-solid name="mail_port" type="number" :label="__('settings.smtp_port')" :value="$values['mail_port']" placeholder="587" />
                    </div>
                </div>

                <div class="d-flex align-items-center mt-3">
                    <button type="button" class="btn btn-light-primary mr-3" id="test-smtp">
                        <i class="la la-paper-plane"></i> {{ __('settings.test_smtp') ?? 'اختبار الإرسال' }}
                    </button>
                    <small class="text-muted">{{ __('settings.test_smtp_hint') ?? 'سيتم إرسال رسالة اختبار إلى بريد المرسِل.' }}</small>
                </div>
            </div>
        </div>

    </x-admin.form-card>
@endsection

@push('styles')
<style>
    /* Better tab borders to merge with card */
    .nav-tabs .nav-link { border: 0; }
    .nav-tabs .nav-link.active { border-bottom: 3px solid var(--primary); }
</style>
@endpush

@push('scripts')
<script>
    (function(){
        // Optional: Test SMTP endpoint (expects JSON {ok:true})
        const btn = document.getElementById('test-smtp');
        if(btn){
            btn.addEventListener('click', function(){
                const url = this.getAttribute('data-url');
                if(!url) return;
                btn.disabled = true; btn.classList.add('spinner');
                fetch(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                    .then(r => r.json())
                    .then(data => {
                        btn.classList.remove('spinner'); btn.disabled = false;
                        if(data.ok){
                            toastr.success(data.message || 'SMTP works!');
                        } else {
                            toastr.error(data.message || 'SMTP failed');
                        }
                    })
                    .catch(() => { btn.classList.remove('spinner'); btn.disabled = false; toastr.error('Request failed'); });
            });
        }
    })();
</script>
@endpush
