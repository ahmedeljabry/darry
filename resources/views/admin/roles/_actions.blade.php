@php(/** @var \Spatie\Permission\Models\Role $r */)
<div class="btn-group" role="group">
    @can('roles.update')
        <a href="{{ route('admin.roles.edit', $r->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('messages.edit') }}"><i class="la la-edit"></i></a>
    @endcan
    @can('roles.delete')
        <form action="{{ route('admin.roles.destroy', $r->id) }}" method="POST" class="d-inline js-delete-form">
            @csrf @method('DELETE')
            <button type="button" class="btn btn-sm btn-clean btn-icon js-delete-btn" title="{{ __('messages.delete') }}"><i class="la la-trash"></i></button>
        </form>
    @endcan
</div>

