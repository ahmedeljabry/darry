<?php
declare(strict_types=1);

namespace App\DataTables;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TenantsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('full_name', fn (Tenant $t) => e($t->full_name))
            ->editColumn('national_id_or_cr', fn (Tenant $t) => e($t->national_id_or_cr))
            ->editColumn('email', fn (Tenant $t) => e($t->email))
            ->editColumn('phone', fn (Tenant $t) => e($t->phone))
            ->editColumn('property_name', fn (Tenant $t) => e($t->property_name ?? $t->property?->name ?? '—'))
            ->editColumn('actions', function (Tenant $t) {
                return view('admin.layouts.partials._actions', [
                    'showRoute' => route('admin.tenants.show', $t->id),
                    'editRoute' => route('admin.tenants.edit', $t->id),
                    'deleteRoute' => route('admin.tenants.destroy', $t->id),
                ])->render();
            })
            ->rawColumns(['actions'])
            ->setRowId('id');
    }

    public function query(Tenant $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('property')
            ->select([
                'tenants.id',
                'tenants.full_name',
                'tenants.national_id_or_cr',
                'tenants.email',
                'tenants.phone',
                'tenants.property_id',
                'properties.name as property_name',
            ])
            ->leftJoin('properties', 'properties.id', '=', 'tenants.property_id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('tenants-table')
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
            Column::make('full_name')->title(__('tenants.full_name')),
            Column::make('national_id_or_cr')->title(__('tenants.national_id_or_cr')),
            Column::make('email')->title(__('tenants.email')),
            Column::make('phone')->title(__('tenants.phone')),
            Column::make('property_name')->title(__('tenants.property')),
            Column::computed('actions')->title(__('messages.actions'))->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Tenants_' . date('YmdHis');
    }
}
