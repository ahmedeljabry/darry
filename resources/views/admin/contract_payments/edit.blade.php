@extends('admin.layouts.master')
@section('title', __('contract_payments.edit'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('contract_payments.title') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="" class="text-muted">{{ __('contract_payments.edit') }}</a></li>
    </ul>
@endsection

@section('content')
    <x-admin.form-card :title="__('contract_payments.edit')" :action="route('admin.contract-payments.update', $contractPayment)" method="PUT" :back="route('admin.contract-payments.index')">
        <div class="row">
            <div class="col-md-4">
                <x-admin.select-solid name="property_id" :label="__('contract_payments.property')" :options="$properties" :value="$contractPayment->property_id" required />
            </div>
            <div class="col-md-4">
                <x-admin.select-solid name="tenant_id" :label="__('contract_payments.tenant')" :options="$tenants" :value="$contractPayment->tenant_id" required />
            </div>
            <div class="col-md-4">
                <x-admin.select-solid name="unit_id" :label="__('contract_payments.unit')" :options="$units->pluck('name','id')" :value="$contractPayment->unit_id" required />
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <x-admin.select-solid name="period_month" :label="__('contract_payments.period_month')" :options="collect(range(1,12))->mapWithKeys(fn($m)=>[$m=>$m])" :value="$contractPayment->period_month" required />
            </div>
            <div class="col-md-3">
                <x-admin.select-solid name="period_year" :label="__('contract_payments.period_year')" :options="collect(range(date('Y')-1,date('Y')+5))->mapWithKeys(fn($y)=>[$y=>$y])" :value="$contractPayment->period_year" required />
            </div>
            <div class="col-md-3">
                <x-admin.input-solid name="amount_due" :label="__('contract_payments.amount_due')" :value="$contractPayment->amount_due" required />
            </div>
            <div class="col-md-3">
                <x-admin.input-solid name="amount_paid" :label="__('contract_payments.amount_paid')" :value="$contractPayment->amount_paid" />
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <x-admin.input-solid name="due_date" type="date" :label="__('contract_payments.due_date')" :value="$contractPayment->due_date?->format('Y-m-d')" required />
            </div>
            <div class="col-md-3">
                <x-admin.input-solid name="paid_at" type="datetime-local" :label="__('contract_payments.paid_at')" :value="$contractPayment->paid_at?->format('Y-m-d\TH:i')" />
            </div>
            <div class="col-md-3">
                <x-admin.select-solid name="method" :label="__('contract_payments.method')" :options="['BANK_TRANSFER' => __('contract_payments.methods.bank_transfer'), 'CHECK' => __('contract_payments.methods.check'), 'CASH' => __('contract_payments.methods.cash')]" :value="$contractPayment->method?->value" required />
            </div>
            <div class="col-md-3">
                <x-admin.input-solid name="due_balance" :label="__('contract_payments.due_balance')" :value="number_format(max(0, $contractPayment->amount_due - $contractPayment->amount_paid), 2)" readonly />
            </div>
        </div>

        <x-admin.textarea-solid name="details" :label="__('contract_payments.details')" :value="$contractPayment->details" />
    </x-admin.form-card>
@endsection

