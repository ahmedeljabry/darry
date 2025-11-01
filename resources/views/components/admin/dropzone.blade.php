@props([
    'name',
    'label',
    'multiple' => false,
    'accept' => null,
    'required' => false,
    'help' => null,
    // existing server-stored file paths (string|array)
    'existing' => null,
    // public URLs matching existing paths (string|array|callable)
    'existingUrls' => null,
])

@php
    $inputId = $attributes->get('id', $name);
    $previewId = $inputId . '_preview';
    $existingFiles = collect(\Illuminate\Support\Arr::wrap($existing))->filter();
    $existingUrlsList = collect(
        is_callable($existingUrls)
            ? $existingFiles->map(fn($path) => $existingUrls($path))
            : \Illuminate\Support\Arr::wrap($existingUrls)
    );
@endphp

<x-admin.field :name="$name" :label="$label" :required="$required" :help="$help">
    <input
        type="file"
        id="{{ $inputId }}"
        name="{{ $name }}@if($multiple)[]@endif"
        @if($multiple) multiple @endif
        @if($accept) accept="{{ $accept }}" @endif
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'form-control form-control-lg form-control-solid js-dropzone-input']) }}
        data-preview="#{{ $previewId }}"
    />

    <div class="mt-4">
        @if($existingFiles->isNotEmpty())
            <div class="mb-3">
                <div class="fw-bold mb-2 text-gray-600">{{ __('messages.current_files') ?? 'الملفات الحالية' }}</div>
                <div class="d-flex flex-wrap gap-3">
                    @foreach($existingFiles as $index => $filePath)
                        @php $url = $existingUrlsList[$index] ?? null; @endphp
                        <div class="border rounded p-2 bg-light">
                            @if($url)
                                <a href="{{ $url }}" target="_blank" class="text-primary small d-block mb-2">
                                    {{ basename((string) $filePath) }}
                                </a>
                                @if(str_starts_with((string) $filePath, 'properties') || (is_string($url) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $url)))
                                    <img src="{{ $url }}" alt="" class="rounded" style="max-width: 140px; max-height: 100px; object-fit: cover;">
                                @endif
                            @else
                                <span class="text-muted small">{{ basename((string) $filePath) }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div id="{{ $previewId }}" class="dropzone-preview text-muted small"></div>
    </div>
</x-admin.field>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.js-dropzone-input').forEach(function (input) {
                    const previewTarget = document.querySelector(input.dataset.preview || '');
                    if (!previewTarget) {
                        return;
                    }

                    const renderPreview = function (files) {
                        if (!files || files.length === 0) {
                            previewTarget.innerHTML = '';
                            return;
                        }

                        const items = Array.from(files).map(function (file) {
                            const isImage = file.type.startsWith('image/');
                            const reader = new FileReader();
                            return new Promise(function (resolve) {
                                reader.onload = function (event) {
                                    const src = event.target?.result;
                                    const html = `
                                        <div class="border rounded p-2 bg-light mb-2 d-flex align-items-center gap-3">
                                            ${isImage && src ? `<img src="${src}" alt="" style="width: 70px; height: 60px; object-fit: cover;" class="rounded">` : ''}
                                            <div>
                                                <div class="fw-bold small mb-1 text-gray-700">${file.name}</div>
                                                <div class="text-muted fs-xs">${Math.round(file.size / 1024)} KB</div>
                                            </div>
                                        </div>`;
                                    resolve(html);
                                };
                                if (isImage) {
                                    reader.readAsDataURL(file);
                                } else {
                                    resolve(`
                                        <div class="border rounded p-2 bg-light mb-2">
                                            <div class="fw-bold small mb-1 text-gray-700">${file.name}</div>
                                            <div class="text-muted fs-xs">${Math.round(file.size / 1024)} KB</div>
                                        </div>`);
                                }
                            });
                        });

                        Promise.all(items).then(function (htmlItems) {
                            previewTarget.innerHTML = htmlItems.join('');
                        });
                    };

                    input.addEventListener('change', function (event) {
                        renderPreview(event.target.files);
                    });
                });
            });
        </script>
    @endpush
@endonce

