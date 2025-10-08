<?php
declare(strict_types=1);

namespace App\DataTables;

use App\Models\Owner;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OwnersDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('status', fn (Owner $o) => $o->status === 'ACTIVE' ? __('owners.active') : __('owners.inactive'))
            ->editColumn('owner_type', fn (Owner $o) => $o->owner_type === 'COMMERCIAL' ? __('owners.types.COMMERCIAL') : __('owners.types.PERSONAL'))
            ->addColumn('actions', function (Owner $o) {
                return view('admin.layouts.partials._actions', [
                    'showRoute' => null,
                    'editRoute' => route('admin.owners.edit', $o->id),
                    'deleteRoute' => route('admin.owners.destroy', $o->id),
                ])->render();
            })
            ->rawColumns(['actions'])
            ->setRowId('id');
    }

    public function query(Owner $model): QueryBuilder
    {
        return $model->newQuery()->select(['id','full_name','id_or_cr','email','phone','address','owner_type','status']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('owners-table')
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
            Column::make('full_name')->title(__('owners.full_name')),
            Column::make('id_or_cr')->title(__('owners.id_or_cr')),
            Column::make('email')->title(__('owners.email')),
            Column::make('phone')->title(__('owners.phone')),
            Column::make('address')->title(__('owners.address')),
            Column::make('owner_type')->title(__('owners.type')),
            Column::make('status')->title(__('owners.status')),
            Column::computed('actions')->title(__('messages.actions'))->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Owners_' . date('YmdHis');
    }
}

