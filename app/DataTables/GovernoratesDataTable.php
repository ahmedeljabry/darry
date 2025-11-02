<?php
declare(strict_types=1);

namespace App\DataTables;

use App\Models\Governorate;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GovernoratesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('country', fn (Governorate $governorate) => $governorate->country?->name ?? '--')
            ->addColumn('states_count', fn (Governorate $governorate) => $governorate->states_count)
            ->addColumn('actions', function (Governorate $governorate) {
                return view('admin.layouts.partials._actions', [
                    'showRoute' => null,
                    'editRoute' => route('admin.governorates.edit', $governorate->id),
                    'deleteRoute' => route('admin.governorates.destroy', $governorate->id),
                ])->render();
            })
            ->rawColumns(['actions'])
            ->setRowId('id');
    }

    public function query(Governorate $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['country:id,name'])
            ->withCount('states')
            ->select(['id','country_id','name']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('governorates-table')
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
            Column::make('name')->title(__('locations.governorate_name')),
            Column::make('country')->title(__('locations.country')),
            Column::make('states_count')->title(__('locations.states_count'))->searchable(false),
            Column::computed('actions')->title(__('messages.actions'))->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Governorates_' . date('YmdHis');
    }
}

