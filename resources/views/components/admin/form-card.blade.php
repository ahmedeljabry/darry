@props([
    'title' => '',
    'action' => '#',
    'method' => 'POST',
    'back' => url()->previous(),
    'submit' => __('messages.save'),
    'headerClass' => 'border-0 d-flex align-items-center justify-content-between',
    'bodyClass' => 'border-top p-9',
    'footerClass' => 'd-flex justify-content-end gap-2',
])

@php($httpMethod = strtoupper($method))
<div class="card mb-5 mb-xl-10">
    <div class="card-header {{ $headerClass }}">
        <div class="card-title">
            <h3 class="fw-bolder m-0">{{ $title }}</h3>
        </div>
        <div class="card-toolbar">
            <a href="{{ $back }}" class="btn btn-light">{{ __('messages.back') }}</a>
        </div>
    </div>
    <form action="{{ $action }}" method="POST" {{ $attributes->merge(['class' => 'form']) }}>
        @csrf
        @if(in_array($httpMethod, ['PUT','PATCH','DELETE']))
            @method($httpMethod)
        @endif
        <div class="card-body {{ $bodyClass }}">
            {{ $slot }}
        </div>

        <div class="card-footer" style="bottom:0; z-index: 999;">
            <div class="card shadow-sm mt-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <i class="la la-shield-alt"></i> {{ __('layout.save_notice') ?? 'تأكّد من صحة القيم قبل الحفظ.' }}
                    </div>
                    <div>
                        <a href="{{ $back }}" class="btn btn-light">{{ __('layout.cancel') ?? 'إلغاء' }}</a>
                         <button type="submit" class="btn btn-primary font-weight-bolder"><i class="bi bi-save"></i>{{ $submit }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
