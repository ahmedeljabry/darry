@php
    $selected = $selected ?? [];
@endphp

<div class="card mb-5">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="fas fa-shield-alt text-primary me-3"></i>
                <div>
                <h5 class="mb-0">{{ __('menu.permissions') }}</h5>
                <small class="text-muted">{{ __('messages.choose_permissions_for_role') }}</small>
                </div>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="checkedAll">
            <label class="form-check-label fw-bold" for="checkedAll">{{ __('messages.select_all') }}</label>
            </div>
        </div>
     </div>

<div class="row g-4" id="permissions-grid">
@foreach($permissionGroups as $group => $perms)
    @php
        $key = \Illuminate\Support\Str::slug($group);
        $permNames = $perms->pluck('name')->all();
        $checkedCount = count(array_intersect($selected, $permNames));
        $allChecked = $checkedCount === count($permNames) && $checkedCount > 0;
    @endphp
    <div class="col-12 col-md-6 col-xl-4">
        <div class="permissionCard card h-100">
            <div class="role-title d-flex justify-content-between align-items-center">
                <span class="text-white">
                    {{ __('menu.' . $group) !== 'menu.' . $group ? __('menu.' . $group) : \Illuminate\Support\Str::headline($group) }}
                </span>
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check m-0 me-3">
                        <input class="form-check-input group-parent roles-parent" type="checkbox" id="{{ $key }}_parent" data-group="{{ $key }}" @checked($allChecked)>
                        <label class="form-check-label text-white-50" for="{{ $key }}_parent">All</label>
                    </div>
                    <div class="form-check m-0">
                        <input class="form-check-input checkChilds checkChilds_{{ $key }}" type="checkbox" id="{{ $key }}" data-parent="{{ $key }}" @checked($allChecked)>
                        <label class="form-check-label text-white-50" for="{{ $key }}">Select</label>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="p-3">
                    <input type="text" class="form-control form-control-sm permission-search" placeholder="{{ __('messages.search') }}" data-group="{{ $key }}">
                </div>
                <ul class="list-unstyled m-0 permission-list" data-group="{{ $key }}">
                    @foreach($perms as $perm)
                        @php
                            $text = str_replace(['.', '_'], ' ', $perm->name);
                        @endphp
                        <li class="permission-item px-3" data-permission-name="{{ $perm->name }}" data-permission-text="{{ strtolower($text) }}">
                            <div class="form-check">
                                <input class="form-check-input childs {{ $key }}" type="checkbox" data-parent="{{ $key }}" name="permissions[]" value="{{ $perm->name }}" @checked(in_array($perm->name, $selected)) id="perm_{{ $key }}_{{ md5($perm->name) }}">
                                <label class="form-check-label title_lable" for="perm_{{ $key }}_{{ md5($perm->name) }}">
                                    {{ __('permissions.' . $perm->name) }}
                                </label>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endforeach
</div>

@push('styles')
<style>
    .permissionCard { border: 1px solid #e4e6ef; border-radius: 12px; }
    .role-title { background: linear-gradient(135deg, #3699FF 0%, #3699FF 100%); padding: .8rem 1rem; }
    .permission-list { max-height: 260px; overflow-y: auto; }
    .permission-item { padding: .4rem 0; border-bottom: 1px solid #f0f0f0; }
    .permission-item:last-child { border-bottom: 0; }
    .title_lable { margin-inline-start: .5rem; }
</style>
@endpush

@push('scripts')
<script>
    (function(){
        function updateGroupState(group){
            var $childs = $('.' + group + '.childs');
            var total = $childs.length;
            var checked = $childs.filter(':checked').length;
            var all = total > 0 && total === checked;
            $('#' + group + '_parent').prop('checked', all);
            $('#' + group).prop('checked', all);
            $('.checkChilds_' + group).prop('checked', all);
        }

        $(document).on('change', '#checkedAll', function(){
            var checked = $(this).is(':checked');
            $('input[type="checkbox"]').prop('checked', checked);
        });

        $(document).on('change', '.group-parent', function(){
            var group = $(this).data('group');
            var checked = $(this).is(':checked');
            $('.' + group).prop('checked', checked);
            updateGroupState(group);
        });

        $(document).on('change', '.checkChilds', function(){
            var group = $(this).data('parent');
            var checked = $(this).is(':checked');
            $('.' + group).prop('checked', checked);
            updateGroupState(group);
        });

        $(document).on('change', '.childs', function(){
            var group = $(this).data('parent');
            updateGroupState(group);
        });

        $(document).on('input', '.permission-search', function(){
            var term = $(this).val().toLowerCase().trim();
            var group = $(this).data('group');
            var $list = $('.permission-list[data-group="' + group + '"]');
            $list.find('.permission-item').each(function(){
                var text = (this.getAttribute('data-permission-text') || '') + ' ' + (this.getAttribute('data-permission-name') || '');
                this.style.display = (!term || text.indexOf(term) !== -1) ? 'block' : 'none';
            });
        });

        // Initialize group states on load
        $(function(){
            $('[id$="_parent"]').each(function(){
                var group = $(this).attr('id').replace(/_parent$/, '');
                updateGroupState(group);
            });
        });
    })();
</script>
@endpush
