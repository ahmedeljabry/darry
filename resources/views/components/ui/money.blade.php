@props([
    'name','label','value' => old($name),
    'currency' => 'ر.ع', 'placeholder' => null, 'help' => null, 'required' => false,
    'step' => '0.01', 'min' => '0'
])
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
    <div class="input-group">
        <span class="input-group-text">{{ $currency }}</span>
        <input type="number" step="{{ $step }}" min="{{ $min }}" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" @if($placeholder) placeholder="{{ $placeholder }}" @endif {{ $isRequired ? 'required' : '' }} {{ $attributes->merge(['class' => $inputClass]) }} />
    </div>
    @if($help)
        <div class="form-text text-muted mt-1">{!! $help !!}</div>
    @endif
    @error($name) <div class="text-danger small mt-1">{{ $message }}</div> @enderror
</div>
