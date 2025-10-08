@props([
    'name','label','value' => old($name),
    'placeholder' => null,'required' => false,'help' => null,
    'rows' => 3, 'size' => 'lg', 'solid' => true,
])
@php($id = $attributes->get('id', $name))
@php($hasError = $errors->has($name))
@php($controlClass = 'form-control' . ($size === 'lg' ? ' form-control-lg' : '') . ($solid ? ' form-control-solid' : '') . ($hasError ? ' is-invalid' : ''))

<x-admin.field :name="$name" :label="$label" :required="$required" :help="$help">
    <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" @if($placeholder) placeholder="{{ $placeholder }}" @endif {{ $required ? 'required' : '' }} {{ $attributes->merge(['class' => $controlClass]) }}>{{ $value }}</textarea>
</x-admin.field>

