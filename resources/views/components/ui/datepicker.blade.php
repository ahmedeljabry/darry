@props(['name','label','value' => old($name), 'placeholder' => '— اختر التاريخ —', 'help' => null, 'required' => false])
@php($id = $attributes->get('id', $name))
@php($isRequired = $required || $attributes->has('required'))
@php($hasError = $errors->has($name))
@php($inputClass = 'form-control' . ($hasError ? ' is-invalid' : ''))
<div class="mb-3">
    <label class="form-label" for="{{ $id }}">
        {{ $label }}
        @if($isRequired)
            <span class="text-danger">*</span>
        @endif
    </label>
    <input type="text" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}" {{ $isRequired ? 'required' : '' }} {{ $attributes->merge(['class' => $inputClass]) }} />
    @if($help)
        <div class="form-text text-muted mt-1">{!! $help !!}</div>
    @endif
    @error($name) <div class="text-danger small mt-1">{{ $message }}</div> @enderror
</div>
<script>document.addEventListener('DOMContentLoaded',() => window.initDatepicker('#{{ $id }}'));</script>
