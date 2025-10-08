@props(['name','label','required' => false, 'help' => null, 'left' => 4, 'right' => 8])
@php($id = $attributes->get('for', $name))
@php($hasError = $errors->has($name))
<div class="row mb-6">
    <label for="{{ $id }}" class="col-lg-{{ $left }} col-form-label {{ $required ? 'required' : '' }} fw-bold">
        {{ $label }} @if ($required) <span class="text-danger">*</span>@endif
    </label>
    <div class="col-lg-{{ $right }}">
        {{ $slot }}
        @if($help)
            <div class="form-text text-muted mt-2">{!! $help !!}</div>
        @endif
        @error($name) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    
</div>

