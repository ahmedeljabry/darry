@extends('admin.layouts.master')
@section('title', __('menu.system_settings'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('menu.system_settings') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="" class="text-muted">{{ __('menu.settings') }}</a></li>
    </ul>
@endsection

@section('content')
    <x-admin.form-card :title="__('menu.system_settings')" :action="route('admin.settings.update')" method="POST" :back="url()->previous()" enctype="multipart/form-data">
        @csrf
        <x-admin.input-solid name="app_name" :label="__('layout.app_name') ?? 'اسم التطبيق'" :value="$values['app_name']" required />
        <x-admin.input-solid name="company_email" :label="__('auth.email')" :value="$values['company_email']" />
        <x-admin.input-solid name="company_phone" :label="__('tenants.phone')" :value="$values['company_phone']" />
        <x-admin.input-solid name="address" :label="__('tenants.address') ?? 'العنوان'" :value="$values['address']" />
        <x-admin.input-solid name="locale" :label="__('layout.locale') ?? 'اللغة'" :value="$values['locale']" />
        <x-admin.input-solid name="currency" :label="__('layout.currency') ?? 'العملة'" :value="$values['currency']" />
        <x-admin.dropzone name="logo" :label="__('layout.logo') ?? 'الشعار'" accept="image/*" />
        <x-admin.dropzone name="favicon" :label="__('layout.favicon') ?? 'الأيقونة'" accept="image/*" />

        <hr>
        <h5 class="mb-4">{{ __('settings.email_settings') }}</h5>
        <div class="row">
            <div class="col-md-4">
                <x-admin.input-solid name="mail_from_address" :label="__('settings.sender_email')" :value="$values['mail_from_address']" />
            </div>
            <div class="col-md-4">
                <x-admin.input-solid name="mail_host" :label="__('settings.smtp_host')" :value="$values['mail_host']" />
            </div>
            <div class="col-md-4">
                <x-admin.input-solid name="mail_username" :label="__('settings.smtp_username')" :value="$values['mail_username']" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <x-admin.select-solid name="mail_driver" :label="__('settings.smtp_driver')" :options="['smtp' => 'SMTP', 'log' => 'Log', 'sendmail' => 'Sendmail']" :value="$values['mail_driver']" />
            </div>
            <div class="col-md-4">
                <x-admin.input-solid name="mail_password" type="password" :label="__('settings.smtp_password')" :value="$values['mail_password']" />
            </div>
            <div class="col-md-2">
                <x-admin.select-solid name="mail_encryption" :label="__('settings.smtp_encryption')" :options="['' => 'None', 'tls' => 'TLS', 'ssl' => 'SSL']" :value="$values['mail_encryption']" />
            </div>
            <div class="col-md-2">
                <x-admin.input-solid name="mail_port" type="number" :label="__('settings.smtp_port')" :value="$values['mail_port']" />
            </div>
        </div>
    </x-admin.form-card>
@endsection
