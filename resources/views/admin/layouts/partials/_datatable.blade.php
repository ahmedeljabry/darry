@push('styles')
   <!--begin::Page Vendors Styles(used by this page)-->
    <link href="{{ asset('admin/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        /* Enhanced DataTables look & feel */
        table.dataTable thead th {
            background-color: #F3F6F9;
            color: #3F4254;
            vertical-align: middle;
        }
        table.dataTable thead tr.filters th { background: #fff; }
        table.dataTable tbody tr:hover { background-color: #F9FBFD; }
    </style>
    <!--end::Page Vendors Styles-->
@endpush

@push('scripts')
    <!--begin::Page Vendors(used by this page)-->
    <script src="{{ asset('admin/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <!--end::Page Vendors-->
    {{ $dataTable->scripts() }}
@endpush
