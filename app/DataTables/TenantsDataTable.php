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
            ->editColumn('email', fn (Tenant $t) => e($t->email))
            ->editColumn('phone', fn (Tenant $t) => e($t->phone))
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
        return $model->newQuery()->select(['id', 'full_name', 'email', 'phone']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('tenants-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->buttons([
                Button::make('excel'), Button::make('csv'), Button::make('pdf'), Button::make('print')
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false),
            Column::make('full_name')->title(__('tenants.full_name')),
            Column::make('email')->title(__('tenants.email')),
            Column::make('phone')->title(__('tenants.phone')),
            Column::computed('actions')->title(__('messages.actions'))->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Tenants_' . date('YmdHis');
    }
}
