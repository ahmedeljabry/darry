@props([
    'name','label','options' => [], 'value' => old($name),
    'placeholder' => '— اختر —','required' => false,'help' => null,
    'select2' => true, 'size' => 'lg', 'solid' => true,
])
@php($id = $attributes->get('id', $name))
@php($hasError = $errors->has($name))
@php($selectBase = 'form-select' . ($size === 'lg' ? ' form-select-lg' : '') . ($solid ? ' form-select-solid' : ''))
@php($selectClass = $selectBase . ($select2 ? ' select2' : '') . ($hasError ? ' is-invalid' : ''))

<x-admin.field :name="$name" :label="$label" :required="$required" :help="$help">
    <select id="{{ $id }}" name="{{ $name }}" data-placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }} {{ $attributes->merge(['class' => $selectClass]) }}>
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $k => $v)
            <option value="{{ $k }}" @selected($value == $k)>{{ $v }}</option>
        @endforeach
    </select>
</x-admin.field>

@if($select2)
@push('scripts')
    <script>
        $(document).ready(function() {
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

