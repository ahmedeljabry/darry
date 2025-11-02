<?php
declare(strict_types=1);

namespace App\DataTables;

use App\Models\State;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StatesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('country', fn (State $state) => $state->governorate?->country?->name ?? '--')
            ->addColumn('governorate', fn (State $state) => $state->governorate?->name ?? '--')
            ->addColumn('actions', function (State $state) {
                return view('admin.layouts.partials._actions', [
                    'showRoute' => null,
                    'editRoute' => route('admin.states.edit', $state->id),
                    'deleteRoute' => route('admin.states.destroy', $state->id),
                ])->render();
            })
            ->rawColumns(['actions'])
            ->setRowId('id');
    }

    public function query(State $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['governorate:id,country_id,name','governorate.country:id,name'])
            ->select(['id','governorate_id','name']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('states-table')
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
            ->buttons([Button::make('excel'), Button::make('csv'), Button::make('pdf'), Button::make('print')]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false),
            Column::make('name')->title(__('locations.state_name')),
            Column::make('country')->title(__('locations.country')),
            Column::make('governorate')->title(__('locations.governorate')),
            Column::computed('actions')->title(__('messages.actions'))->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'States_' . date('YmdHis');
    }
}

