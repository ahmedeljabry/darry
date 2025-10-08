@props([
    'name',
    'label',
    'type' => 'text',
    'value' => old($name),
    'placeholder' => null,
    'help' => null,
    'required' => false,
    'prepend' => null,
    'append' => null,
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

    @if($prepend || $append)
        <div class="input-group">
            @if($prepend)
                <span class="input-group-text">{!! $prepend !!}</span>
            @endif
            <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" @if($placeholder) placeholder="{{ $placeholder }}" @endif {{ $isRequired ? 'required' : '' }} {{ $attributes->merge(['class' => $inputClass]) }} />
            @if($append)
                <span class="input-group-text">{!! $append !!}</span>
            @endif
        </div>
    @else
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" @if($placeholder) placeholder="{{ $placeholder }}" @endif {{ $isRequired ? 'required' : '' }} {{ $attributes->merge(['class' => $inputClass]) }} />
    @endif

    @if($help)
        <div class="form-text text-muted mt-1">{!! $help !!}</div>
    @endif
    @error($name) <div class="text-danger small mt-1">{{ $message }}</div> @enderror
</div>
