@props([
    'name','label','type' => 'text','value' => old($name),
    'placeholder' => null,'required' => false,'help' => null,
    'icon' => null,'size' => 'lg','solid' => true,
])
@php($id = $attributes->get('id', $name))
@php($hasError = $errors->has($name))
@php($controlClass = 'form-control' . ($size === 'lg' ? ' form-control-lg' : '') . ($solid ? ' form-control-solid' : '') . ($hasError ? ' is-invalid' : ''))

<x-admin.field :name="$name" :label="$label" :required="$required" :help="$help">
    @if($icon)
        <div class="input-group">
            <span class="input-group-text"><i class="{{ $icon }}"></i></span>
            <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" @if($placeholder) placeholder="{{ $placeholder }}" @endif {{ $required ? 'required' : '' }} {{ $attributes->merge(['class' => $controlClass]) }} />
        </div>
    @else
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" @if($placeholder) placeholder="{{ $placeholder }}" @endif {{ $required ? 'required' : '' }} {{ $attributes->merge(['class' => $controlClass]) }} />
    @endif
</x-admin.field>

