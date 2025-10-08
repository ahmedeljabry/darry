@extends('admin.layouts.master')

@section('title', __('messages.dashboard'))
@section('breadcrumbs')
    <!--begin::Page Title-->
    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ __('messages.dashboard') }}</h5>
    <!--end::Page Title-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="" class="text-muted">{{ __('messages.dashboard') }}</a>
        </li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')
<!--Begin::Row-->
<div class="row">
  <div class="col-xl-3">
    <div class="card card-custom bgi-no-repeat card-stretch gutter-b"
         style="background-position: right top; background-size: 30% auto; background-image: url(assets/media/svg/shapes/abstract-1.svg)">
      <div class="card-body">
        <span class="svg-icon svg-icon-2x svg-icon-info">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd"><rect width="24" height="24"/><path d="M6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,12 C19,12.5522847 18.5522847,13 18,13 L6,13 C5.44771525,13 5,12.5522847 5,12 L5,3 C5,2.44771525 5.44771525,2 6,2 Z" fill="#000" opacity=".3"/><path d="M3.79274528,6.57253826 L12,12.5 L20.2072547,6.57253826 L21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 Z" fill="#000"/></g></svg>
        </span>
        <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block" data-count="{{ $kpiUnits }}">{{ $kpiUnits }}</span>
        <span class="font-weight-bold text-muted font-size-sm">عدد الوحدات</span>
        <a href="{{ route('admin.dashboard') }}" class="stretched-link"></a>
      </div>
    </div>
  </div>

  <div class="col-xl-3">
    <div class="card card-custom bgi-no-repeat card-stretch gutter-b"
         style="background-position: right top; background-size: 30% auto; background-image: url(assets/media/svg/shapes/abstract-2.svg)">
      <div class="card-body">
        <span class="svg-icon svg-icon-2x svg-icon-success">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd"><rect width="24" height="24"/><path d="M5,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,19 C21,20.1045695 20.1045695,21 19,21 L5,21 C3.8954305,21 3,20.1045695 3,19 L3,5 C3,3.8954305 3.8954305,3 5,3 Z" fill="#000" opacity=".3"/><path d="M7,10 L11,14 L17,8" stroke="#000" stroke-width="2"/></g></svg>
        </span>
        <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block" data-count="{{ $kpiVacantUnits ?? 0 }}">{{ $kpiVacantUnits ?? 0 }}</span>
        <span class="font-weight-bold text-muted font-size-sm">الوحدات الشاغرة</span>
        <a href="{{ route('admin.units.index') }}" class="stretched-link"></a>
      </div>
    </div>
  </div>

  <div class="col-xl-3">
    <div class="card card-custom bgi-no-repeat card-stretch gutter-b"
         style="background-position: right top; background-size: 30% auto; background-image: url(assets/media/svg/shapes/abstract-3.svg)">
      <div class="card-body">
        <span class="svg-icon svg-icon-2x svg-icon-warning">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd"><rect width="24" height="24"/><path d="M12,2 L22,20 L2,20 L12,2 Z" fill="#000" opacity=".3"/><rect x="11" y="10" width="2" height="5" rx="1" fill="#000"/><rect x="11" y="16" width="2" height="2" rx="1" fill="#000"/></g></svg>
        </span>
        <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block" data-count="{{ $kpiContractsNearExpiry ?? 0 }}">{{ $kpiContractsNearExpiry ?? 0 }}</span>
        <span class="font-weight-bold text-muted font-size-sm">عقود قريبة الانتهاء</span>
        <a href="{{ route('admin.contracts.index') }}" class="stretched-link"></a>
      </div>
    </div>
  </div>

@endsection
