@props([
    'name',
    'label',
    'options' => [],
    'value' => old($name),
    'placeholder' => '— اختر —',
    'required' => false,
    'help' => null,
    'select2' => true,
    'size' => 'lg',
    'solid' => true,
])
@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);
    $selectBase = 'form-select' . ($size === 'lg' ? ' form-select-lg' : '') . ($solid ? ' form-select-solid' : '');
    $selectClass = $selectBase . ($select2 ? ' select2' : '') . ($hasError ? ' is-invalid' : '');
    $isMultiple = $attributes->has('multiple') || str_ends_with($name, '[]');
    $selectedValues = $isMultiple ? collect((array) $value)->map(fn($v) => (string) $v)->all() : (string) $value;
@endphp

<x-admin.field :name="$name" :label="$label" :required="$required" :help="$help">
    <select
        id="{{ $id }}"
        name="{{ $name }}"
        data-placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => $selectClass]) }}
    >
        @unless($isMultiple)
            <option value="">{{ $placeholder }}</option>
        @endunless
        @foreach($options as $k => $v)
            @php($optionValue = (string) $k)
            <option value="{{ $optionValue }}"
                @if($isMultiple)
                    @selected(in_array($optionValue, $selectedValues, true))
                @else
                    @selected($selectedValues === $optionValue)
                @endif
            >
                {{ $v }}
            </option>
        @endforeach
    </select>
</x-admin.field>

@if($select2)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                $('.select2').each(function () {
                    const $el = $(this);
                    $el.select2({
                        width: '100%',
                        placeholder: $el.data('placeholder') || '— اختر —',
                        allowClear: !$el.prop('required')
                    });
                });
            });
        </script>
    @endpush
@endif

