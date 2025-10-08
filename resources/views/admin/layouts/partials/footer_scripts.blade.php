<script>var HOST_URL = "/";</script>
<!--begin::Global Config(global config for global JS scripts)-->
<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
<!--end::Global Config-->
<!--begin::Global Theme Bundle(used by all pages)-->
<script src="{{ asset('admin/assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('admin/assets/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
<script src="{{ asset('admin/assets/js/scripts.bundle.js') }}"></script>
<script src="https://keenthemes.com/metronic/assets/js/engage_code.js"></script>
<!--end::Global Theme Bundle-->

<script src="{{ asset('admin/assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
<script src="{{ asset('admin/assets/js/pages/features/miscellaneous/bootstrap-notify.js') }}"></script>
<script src="{{ asset('admin/assets/js/pages/features/miscellaneous/sweetalert2.js') }}"></script>
<script src="{{ asset('admin/assets/js/pages/features/miscellaneous/sweetalert2.min.js') }}"></script>

<script type="text/javascript">
    window.appNotify = function(type, message) {
        if (!window.jQuery || !$.notify) return;
        $.notify({
            message: message
        },{
            type: type,
            z_index: 9999,
            allow_dismiss: true,
            newest_on_top: true,
            placement: { from: 'top', align: 'right' },
            offset: { x: 20, y: 70 },
            delay: 4000,
            animate: { enter: 'animate__animated animate__fadeInDown', exit: 'animate__animated animate__fadeOutUp' }
        });
    };
    @if (session('success') || session('status'))
        appNotify('success', @json(session('success') ?? session('status')));
    @endif
    @if (session('error'))
        appNotify('danger', @json(session('error')));
    @endif
    
    (function(){
        function bindSwalDelete(){
            if (!window.Swal) return;
            document.querySelectorAll('.js-delete-btn').forEach(function(btn){
                if (btn.dataset.bound === '1') return;
                btn.dataset.bound = '1';
                btn.addEventListener('click', function(e){
                    var form = e.currentTarget.closest('form');
                    if (!form) return;
                    Swal.fire({
                        title: @json(__('messages.confirm_delete')),
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: @json(__('messages.delete')),
                        cancelButtonText: @json(__('messages.cancel')),
                        customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-light' },
                        buttonsStyling: false,
                        reverseButtons: document.documentElement.getAttribute('dir') === 'rtl'
                    }).then(function(result){
                        if (result.isConfirmed) { form.submit(); }
                    });
                });
            });
        }
        document.addEventListener('DOMContentLoaded', bindSwalDelete);
        $(document).on('draw.dt', function(){ bindSwalDelete(); });
    })();
</script>

@stack('scripts')

<script>
    // Global SweetAlert2 delete binder (works for DataTables and static content)
    (function(){
        function bindSwalDelete(){
            if (!window.Swal) return;
            document.querySelectorAll('.js-delete-btn').forEach(function(btn){
                if (btn.dataset.bound === '1') return;
                btn.dataset.bound = '1';
                btn.addEventListener('click', function(e){
                    var form = e.currentTarget.closest('form');
                    if (!form) return;
                    Swal.fire({
                        title: @json(__('messages.confirm_delete')),
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: @json(__('messages.delete')),
                        cancelButtonText: @json(__('messages.cancel')),
                        customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-light' },
                        buttonsStyling: false,
                        reverseButtons: document.documentElement.getAttribute('dir') === 'rtl'
                    }).then(function(result){
                        if (result.isConfirmed) { form.submit(); }
                    });
                });
            });
        }
        document.addEventListener('DOMContentLoaded', bindSwalDelete);
        if (window.jQuery) {
            $(document).on('draw.dt', bindSwalDelete);
        }
    })();
</script>
