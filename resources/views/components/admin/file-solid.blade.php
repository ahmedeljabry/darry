@props([
    'name', 'label', 'required' => false, 'help' => null,
    'accept' => null, 'multiple' => false, 'size' => 'lg', 'solid' => true,
])
@php($id = $attributes->get('id', $name))
@php($hasError = $errors->has($name))
@php($inputClass = 'form-control' . ($size === 'lg' ? ' form-control-lg' : '') . ($solid ? ' form-control-solid' : '') . ($hasError ? ' is-invalid' : ''))

<x-admin.field :name="$name" :label="$label" :required="$required" :help="$help">
    <input type="file" id="{{ $id }}" name="{{ $name }}{{ $multiple ? '[]' : '' }}" {{ $multiple ? 'multiple' : '' }} @if($accept) accept="{{ $accept }}" @endif {{ $required ? 'required' : '' }} {{ $attributes->merge(['class' => $inputClass]) }} />
</x-admin.field>

