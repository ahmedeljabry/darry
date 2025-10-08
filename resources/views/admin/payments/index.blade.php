@extends('admin.layouts.master')

@section('breadcrumb', __('payments.title'))

@section('content')
    <h3 class="mb-4">{{ __('payments.title') }}</h3>
    <table id="paymentsTable" class="table table-striped table-hover" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('invoices.title') }}</th>
                <th>{{ __('payments.amount') }}</th>
                <th>{{ __('payments.method') }}</th>
                <th>{{ __('payments.paid_at') }}</th>
            </tr>
        </thead>
    </table>
    @push('scripts')
    <script>
        $(function(){
            $('#paymentsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.payments.data') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'invoice_id', name: 'invoice_id' },
                    { data: 'amount', name: 'amount' },
                    { data: 'method', name: 'method' },
                    { data: 'paid_at', name: 'paid_at' },
                ]
            });
        });
    </script>
    @endpush
@endsection
