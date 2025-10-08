@extends('admin.layouts.master')
@section('title', __('expenses.create'))

@section('breadcrumbs')
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('expenses.title') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted"><a href="" class="text-muted">{{ __('expenses.create') }}</a></li>
    </ul>
@endsection

@section('content')
    <x-admin.form-card :title="__('expenses.create')" :action="route('admin.expenses.store')" :back="route('admin.expenses.index')">
                <x-admin.input-solid name="title" :label="__('expenses.title_field')" required />

                <x-admin.input-solid name="date" type="date" :label="__('expenses.date')" required />            
                <x-admin.input-solid name="amount" type="number" step="0.001" :label="__('expenses.amount')" placeholder="1234556.123" required />

            
                <x-admin.input-solid name="receipt_no" :label="__('expenses.receipt_no')" />
            
                <x-admin.select-solid name="property_id" id="expense_property_id" :label="__('expenses.property')" :options="$properties" required />
            
                <x-admin.select-solid name="unit_id" id="expense_unit_id" :label="__('expenses.unit')" :options="$units->pluck('name','id')" />
            
                <x-admin.input-solid name="category" :label="__('expenses.category')" />
    </x-admin.form-card>
@endsection

