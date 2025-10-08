@extends('admin.layouts.master')
@section('title', __('contracts.edit'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('contracts.title') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="" class="text-muted">{{ __('contracts.edit') }}</a></li>
    </ul>
@endsection

@section('content')
    <x-admin.form-card :title="__('contracts.edit')" :action="route('admin.contracts.update', $contract)" method="PUT" :back="route('admin.contracts.index')">
  
                <x-admin.input-solid name="contract_no" :label="__('contracts.contract_no')" :value="$contract->contract_no" />
   
                <x-admin.input-solid name="start_date" type="date" :label="__('contracts.start_date')" :value="$contract->start_date?->format('Y-m-d')" required />
         
                <x-admin.input-solid name="duration_months" type="number" min="1" :label="__('contracts.duration')" :value="$contract->duration_months" required id="duration_months" />
  

     
                <x-admin.input-solid name="end_date" type="date" :label="__('contracts.end_date')" :value="$contract->end_date?->format('Y-m-d')" readonly id="end_date" />
         
                <x-admin.select-solid name="property_id" id="contract_property_id" :label="__('contracts.property')" :options="$properties" :value="$contract->property_id" required />
         
                <x-admin.select-solid name="unit_id" id="contract_unit_id" :label="__('contracts.unit')" :options="$units->pluck('name','id')" :value="$contract->unit_id" required />
     

     
                <x-admin.select-solid name="tenant_id" :label="__('contracts.tenant')" :options="$tenants" :value="$contract->tenant_id" required />
       
                <x-admin.select-solid name="payment_method" :label="__('contracts.method')" :options="['BANK_TRANSFER' => __('contract_payments.methods.bank_transfer'), 'CHECK' => __('contract_payments.methods.check'), 'CASH' => __('contract_payments.methods.cash')]" :value="$contract->payment_method?->value" />
         
                <x-admin.input-solid name="payment_day" type="number" min="1" max="31" :label="__('contracts.payment_day')" :value="$contract->payment_day" />
    
       
                <x-admin.input-solid name="rent_amount" :label="__('contracts.rent_amount')" id="rent_amount_input" :value="$contract->rent_amount" />
      
    </x-admin.form-card>
@endsection

