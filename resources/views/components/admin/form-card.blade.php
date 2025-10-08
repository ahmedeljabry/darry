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

        <div class="card-footer {{ $footerClass }}">
            <button type="submit" class="btn btn-primary font-weight-bolder"><i class="bi bi-save"></i>{{ $submit }}</button>
            <a href="{{ $back }}" class="btn btn-light"> {{ __('messages.back') }}</a>
        </div>
    </form>
</div>
