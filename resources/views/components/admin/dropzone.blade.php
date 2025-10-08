@props([
  'name',
  'label',
  'multiple' => false,
  'accept' => null,
  'required' => false,
  'help' => null,
  // existing server-stored file paths (string|array)
  'existing' => null,
  // public URLs matching existing paths (string|array)
  'existingUrls' => null,
])

<x-admin.field :name="$name" :label="$label" :required="$required" :help="$help">
  <input type="file" id="{{ isset($inputId) ?? '' }}" name="{{ $name }}@if($multiple)[]@endif" @if($multiple) multiple @endif @if($accept) accept="{{ $accept }}" @endif />
</x-admin.field>


