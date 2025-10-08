<?php
declare(strict_types=1);

namespace App\DataTables;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FacilitiesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('created_at' , fn ($f) => $f->created_at?->format('Y-m-d h:i A'))
            ->addColumn('actions', function (Facility $f) {
                return view('admin.layouts.partials._actions', [
                    'showRoute' => null,
                    'editRoute' => route('admin.facilities.edit', $f->id),
                    'deleteRoute' => route('admin.facilities.destroy', $f->id),
                ])->render();
            })
            ->rawColumns(['actions'])
            ->setRowId('id');
    }

    public function query(Facility $model): QueryBuilder
    {
        return $model->newQuery()->select(['id','name','created_at']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('facilities-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->buttons([Button::make('excel'), Button::make('csv'), Button::make('pdf'), Button::make('print')]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false),
            Column::make('name')->title(__('facilities.name')),
            Column::make('created_at')->title(__('messages.created_at'))->searchable(false),
            Column::computed('actions')->title(__('messages.actions'))->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Facilities_' . date('YmdHis');
    }
}

