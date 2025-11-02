<?php
declare(strict_types=1);

namespace App\DataTables;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ContractsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('contract_no', fn (Contract $c) => e($c->contract_no))
            ->addColumn('property', fn (Contract $c) => e($c->property->name ?? '—'))
            ->addColumn('unit', fn (Contract $c) => e($c->unit->name ?? '—'))
            ->addColumn('tenant', fn (Contract $c) => e($c->tenant->full_name ?? '—'))
            ->editColumn('start_date', fn (Contract $c) => optional($c->start_date)->format('Y-m-d'))
            ->editColumn('end_date', fn (Contract $c) => optional($c->end_date)->format('Y-m-d'))
            ->editColumn('rent_amount', fn (Contract $c) => number_format((float) ($c->rent_amount ?? 0), 2))
            ->addColumn('method_label', function (Contract $c) {
                $key = strtolower($c->payment_method->value ?? 'cash');
                return __("contract_payments.methods.$key");
            })
            ->addColumn('actions', function (Contract $c) {
                return view('admin.layouts.partials._actions', [
                    'showRoute' => null,
                    'editRoute' => route('admin.contracts.edit', $c->id),
                    'deleteRoute' => route('admin.contracts.destroy', $c->id),
                ])->render();
            })
            ->rawColumns(['actions'])
            ->setRowId('id');
    }

    public function query(Contract $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['property','unit','tenant'])
            ->select(['id','contract_no','property_id','unit_id','tenant_id','start_date','end_date','rent_amount','payment_method']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('contracts-table')
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
            Column::make('contract_no')->title(__('contracts.contract_no')),
            Column::make('property')->title(__('contracts.property')),
            Column::make('unit')->title(__('contracts.unit')),
            Column::make('tenant')->title(__('contracts.tenant')),
            Column::make('start_date')->title(__('contracts.start_date')),
            Column::make('end_date')->title(__('contracts.end_date')),
            Column::make('rent_amount')->title(__('contracts.rent_amount')),
            Column::make('method_label')->title(__('contracts.method')),
            Column::computed('actions')->title(__('messages.actions'))->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Contracts_' . date('YmdHis');
    }
}
