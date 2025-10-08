@extends('admin.layouts.master')
@section('title', __('contract_payments.create'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('contract_payments.title') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="" class="text-muted">{{ __('contract_payments.create') }}</a></li>
    </ul>
@endsection

@section('content')
    <x-admin.form-card :title="__('contract_payments.create')" :action="route('admin.contract-payments.store')" :back="route('admin.contract-payments.index')">
                <x-admin.select-solid name="property_id" :label="__('contract_payments.property')" :options="$properties" required />

                <x-admin.select-solid name="tenant_id" :label="__('contract_payments.tenant')" :options="$tenants" required />

                <x-admin.select-solid name="unit_id" :label="__('contract_payments.unit')" :options="$units->pluck('name','id')" required id="unit_id_select" />
            
                <x-admin.select-solid name="period_month" :label="__('contract_payments.period_month')" :options="collect(range(1,12))->mapWithKeys(fn($m)=>[$m=>$m])" required />
            
                <x-admin.select-solid name="period_year" :label="__('contract_payments.period_year')" :options="collect(range(date('Y')-1,date('Y')+5))->mapWithKeys(fn($y)=>[$y=>$y])" required />
            
                <x-admin.input-solid name="amount_due" :label="__('contract_payments.amount_due')" placeholder="0.00" required id="amount_due_input" />
            
                <x-admin.input-solid name="amount_paid" :label="__('contract_payments.amount_paid')" placeholder="0.00" id="amount_paid_input" />

                <x-admin.input-solid name="due_date" type="date" :label="__('contract_payments.due_date')" required />
            
                <x-admin.input-solid name="paid_at" type="datetime-local" :label="__('contract_payments.paid_at')" />
            
                <x-admin.select-solid name="method" :label="__('contract_payments.method')" :options="['BANK_TRANSFER' => __('contract_payments.methods.bank_transfer'), 'CHECK' => __('contract_payments.methods.check'), 'CASH' => __('contract_payments.methods.cash')]" required />
            
                <x-admin.input-solid name="due_balance" :label="__('contract_payments.due_balance')" placeholder="0.00" value="0" />

        <x-admin.textarea-solid name="details" :label="__('contract_payments.details')" />

    </x-admin.form-card>
@endsection

