@props([
    'name', 'label', 'options' => [], 'value' => old($name),
    'placeholder' => '— اختر —', 'help' => null, 'required' => false,
])
@php($id = $attributes->get('id', $name))
@php($isRequired = $required || $attributes->has('required'))
@php($hasError = $errors->has($name))
@php($selectClass = 'select2' . ($hasError ? ' is-invalid' : ''))
<div class="mb-3">
    <label class="form-label" for="{{ $id }}">
        {{ $label }}
        @if($isRequired)
            <span class="text-danger">*</span>
        @endif
    </label>
    <select id="{{ $id }}" name="{{ $name }}" data-placeholder="{{ $placeholder }}" {{ $isRequired ? 'required' : '' }} {{ $attributes->merge(['class' => $selectClass]) }}>
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $k => $v)
            <option value="{{ $k }}" @selected($value == $k)>{{ $v }}</option>
        @endforeach
    </select>
    @if($help)
        <div class="form-text text-muted mt-1">{!! $help !!}</div>
    @endif
    @error($name) <div class="text-danger small mt-1">{{ $message }}</div> @enderror
</div>

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
