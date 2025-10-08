<div class="btn-group" role="group" aria-label="Actions">
    @isset($showRoute)
        @if(!empty($showRoute))
            <a href="{{ $showRoute }}" class="btn btn-icon btn-light-primary btn-sm rf-book" title="{{ __('messages.show') }}">
                <i class="la la-eye"></i>
            </a>
        @endif
    @endisset

    @isset($editRoute)
        @if(!empty($editRoute))
            <a href="{{ $editRoute }}" class="btn btn-icon btn-light-warning btn-sm" title="{{ __('messages.edit') }}">
                <i class="la la-edit"></i>
            </a>
        @endif
    @endisset

    @isset($deleteRoute)
        @if(!empty($deleteRoute))
            <form action="{{ $deleteRoute }}" method="POST" class="d-inline js-delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-icon btn-light-danger btn-sm js-delete-btn" title="{{ __('messages.delete') }}">
                    <i class="la la-trash"></i>
                </button>
            </form>
        @endif
    @endisset
</div>