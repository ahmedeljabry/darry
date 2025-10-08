@props([
    'name',
    'label',
    'value' => old($name),
    'currency' => 'ر.ع',
    'placeholder' => null,
    'help' => null,
    'required' => false,
    'step' => '0.01',
    'min' => '0',
    'size' => 'lg',
    'solid' => true,
])
@php($id = $attributes->get('id', $name))
@php($hasError = $errors->has($name))
@php($inputClass = 'form-control' . ($size === 'lg' ? ' form-control-lg' : '') . ($solid ? ' form-control-solid' : '') . ($hasError ? ' is-invalid' : ''))


           
           <x-admin.field :name="$name" :label="$label" :required="$required" :help="$help">
    <div class="input-group">
        <span class="input-group-text">{{ $currency }}</span>
        <input type="number" step="{{ $step }}" min="{{ $min }}" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" @if($placeholder) placeholder="{{ $placeholder }}" @endif {{ $required ? 'required' : '' }} {{ $attributes->merge(['class' => $inputClass]) }} />
    </div>
</x-admin.field>

