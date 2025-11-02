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

@php
    $acceptLabel = $accept
        ? collect(explode(',', $accept))
            ->map(fn ($type) => trim($type))
            ->map(function ($type) {
                return match ($type) {
                    'image/*' => __('messages.accept_images') ?? 'ملفات الصور',
                    'application/pdf' => 'PDF',
                    'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => __('messages.accept_docs') ?? 'مستندات Word',
                    default => strtoupper($type),
                };
            })
            ->join('، ')
        : null;
@endphp

<x-admin.field :name="$name" :label="$label" :required="$required" :help="$help">
    <div class="az-dropzone" data-input="{{ $inputId }}">
        <input
            type="file"
            id="{{ $inputId }}"
            name="{{ $name }}@if($multiple)[]@endif"
            @if($multiple) multiple @endif
            @if($accept) accept="{{ $accept }}" @endif
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'd-none js-dropzone-input']) }}
            data-preview="#{{ $previewId }}"
        />
        <div class="az-dropzone__content">
            <div class="az-dropzone__icon">
                <i class="la la-cloud-upload-alt"></i>
            </div>
            <div class="az-dropzone__text">
                <span class="az-dropzone__title">{{ __('messages.dropzone_title') ?? 'اسحب الملفات إلى هنا أو انقر للاختيار' }}</span>
                <span class="az-dropzone__hint">
                    {{ $acceptLabel ?? __('messages.dropzone_default_hint') ?? 'بإمكانك رفع عدة ملفات دفعة واحدة' }}
                </span>
                <button type="button" class="btn btn-sm btn-light-primary mt-3">
                    <i class="la la-folder-open ms-1"></i>{{ __('messages.choose_files') ?? 'اختر الملفات' }}
                </button>
            </div>
        </div>
    </div>

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

                    const zone = input.closest('.az-dropzone');
                    if (!zone) {
                        return;
                    }

                    const openDialog = () => input.click();
                    zone.querySelector('.az-dropzone__content')?.addEventListener('click', openDialog);
                    zone.addEventListener('click', function (event) {
                        if (event.target === zone) {
                            openDialog();
                        }
                    });

                    const preventDefaults = (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                    };

                    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function (eventName) {
                        zone.addEventListener(eventName, preventDefaults, false);
                    });

                    ['dragenter', 'dragover'].forEach(function (eventName) {
                        zone.addEventListener(eventName, function () {
                            zone.classList.add('is-dragover');
                        }, false);
                    });

                    ['dragleave', 'drop'].forEach(function (eventName) {
                        zone.addEventListener(eventName, function () {
                            zone.classList.remove('is-dragover');
                        }, false);
                    });

                    zone.addEventListener('drop', function (e) {
                        const files = e.dataTransfer.files;
                        if (!files || !files.length) {
                            return;
                        }

                        const dataTransfer = new DataTransfer();
                        const limit = input.multiple ? files.length : 1;
                        for (let i = 0; i < limit; i++) {
                            dataTransfer.items.add(files[i]);
                        }
                        input.files = dataTransfer.files;
                        input.dispatchEvent(new Event('change'));
                    });
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .az-dropzone {
                position: relative;
                border: 2px dashed rgba(58, 87, 232, 0.3);
                border-radius: 1rem;
                background: rgba(58, 87, 232, 0.04);
                transition: all 0.2s ease;
                cursor: pointer;
            }
            .az-dropzone.is-dragover {
                border-color: rgba(58, 87, 232, 0.7);
                background: rgba(58, 87, 232, 0.08);
                box-shadow: 0 0 0 0.35rem rgba(58, 87, 232, 0.1);
            }
            .az-dropzone__content {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 1.5rem;
                padding: 2.5rem 1.5rem;
            }
            .az-dropzone__icon {
                width: 64px;
                height: 64px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(58, 87, 232, 0.12);
                color: #3A57E8;
                font-size: 1.75rem;
            }
            .az-dropzone__text {
                display: flex;
                flex-direction: column;
                gap: 0.35rem;
                text-align: start;
            }
            .az-dropzone__title {
                font-weight: 600;
                color: #1E1E2D;
            }
            .az-dropzone__hint {
                font-size: 0.9rem;
                color: #7E8299;
            }
            .az-dropzone button.btn {
                width: fit-content;
            }
            .dropzone-preview .border {
                border-color: rgba(58, 87, 232, 0.25) !important;
            }
        </style>
    @endpush
@endonce
