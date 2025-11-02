<?php
declare(strict_types=1);

namespace App\DataTables;

use App\Models\ContractPayment;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ContractPaymentsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('property', fn (ContractPayment $p) => e($p->property->name ?? '—'))
            ->addColumn('unit', fn (ContractPayment $p) => e($p->unit->name ?? '—'))
            ->addColumn('tenant', fn (ContractPayment $p) => e($p->tenant->full_name ?? '—'))
            ->addColumn('period', fn (ContractPayment $p) => $p->period_month . '/' . $p->period_year)
            ->editColumn('amount_due', fn (ContractPayment $p) => number_format((float) $p->amount_due, 2))
            ->editColumn('amount_paid', fn (ContractPayment $p) => number_format((float) $p->amount_paid, 2))
            ->addColumn('method_label', function (ContractPayment $p) {
                $key = $p->method?->value ?? 'CASH';
                $key = strtolower($key);
                return __("contract_payments.methods.$key");
            })
            ->addColumn('actions', function (ContractPayment $p) {
                return view('admin.layouts.partials._actions', [
                    'showRoute' => null,
                    'editRoute' => route('admin.contract-payments.edit', $p->id),
                    'deleteRoute' => route('admin.contract-payments.destroy', $p->id),
                ])->render();
            })
            ->rawColumns(['actions'])
            ->setRowId('id');
    }

    public function query(ContractPayment $model): QueryBuilder
    {
        return $model->newQuery()->with(['property','unit','tenant'])
            ->select(['id','property_id','unit_id','tenant_id','period_month','period_year','amount_due','amount_paid','method']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('contract-payments-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addTableClass('table table-row-dashed table-hover align-middle gy-4')
            ->orderBy(1)
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'stateSave' => true,
                'pageLength' => 10,
                'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, __('messages.all') ?? 'الكل']],
            ])
            ->buttons([
                Button::make('excel'), Button::make('csv'), Button::make('pdf'), Button::make('print')
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false),
            Column::make('property')->title(__('contract_payments.property')),
            Column::make('unit')->title(__('contract_payments.unit')),
            Column::make('tenant')->title(__('contract_payments.tenant')),
            Column::make('period')->title(__('contract_payments.period')),
            Column::make('amount_due')->title(__('contract_payments.amount_due')),
            Column::make('amount_paid')->title(__('contract_payments.amount_paid')),
            Column::make('method_label')->title(__('contract_payments.method')),
            Column::computed('actions')->title(__('messages.actions'))->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'ContractPayments_' . date('YmdHis');
    }
}
