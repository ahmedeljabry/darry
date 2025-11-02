@props([
    'title' => '',
    'action' => '#',
    'method' => 'POST',
    'back' => url()->previous(),
    'submit' => __('messages.save'),
    'bodyClass' => 'p-8 pt-0',
])

@php($httpMethod = strtoupper($method))
<form action="{{ $action }}" method="POST" {{ $attributes->merge(['class' => 'form']) }}>
    @csrf
    @if(in_array($httpMethod, ['PUT','PATCH','DELETE']))
        @method($httpMethod)
    @endif

    <div class="card shadow-sm mb-10">
        <div class="card-header border-0 py-5 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex flex-column">
                <span class="card-title fw-bold fs-3">{{ $title }}</span>
                @isset($subtitle)
                    <span class="text-muted mt-1">{{ $subtitle }}</span>
                @endisset
            </div>
            @if($back)
                <a href="{{ $back }}" class="btn btn-light btn-sm px-4">
                    <i class="la la-arrow-right text-muted ms-2"></i>{{ __('messages.back') }}
                </a>
            @endif
        </div>

        <div class="card-body {{ $bodyClass }}">
            <div class="row g-5">
                <div class="col-12">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <div class="card-footer border-0 bg-light py-4 px-5 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="text-muted small d-flex align-items-center gap-2">
                <i class="la la-info-circle fs-4 text-primary"></i>
                <span>{{ __('layout.save_notice') ?? 'تأكّد من صحة القيم قبل الحفظ.' }}</span>
            </div>
            <div class="d-flex gap-3">
                @if($back)
                    <a href="{{ $back }}" class="btn btn-light px-5">
                        {{ __('layout.cancel') ?? 'إلغاء' }}
                    </a>
                @endif
                <button type="submit" class="btn btn-primary px-6">
                    <i class="la la-save ms-2"></i>{{ $submit }}
                </button>
            </div>
        </div>
    </div>
</form>
