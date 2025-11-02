<?php
declare(strict_types=1);

namespace App\DataTables;

use App\Models\Country;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CountriesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('governorates_count', fn (Country $country) => $country->governorates_count)
            ->addColumn('actions', function (Country $country) {
                return view('admin.layouts.partials._actions', [
                    'showRoute' => null,
                    'editRoute' => route('admin.countries.edit', $country->id),
                    'deleteRoute' => route('admin.countries.destroy', $country->id),
                ])->render();
            })
            ->rawColumns(['actions'])
            ->setRowId('id');
    }

    public function query(Country $model): QueryBuilder
    {
        return $model->newQuery()
            ->withCount('governorates')
            ->select(['id','name','code']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('countries-table')
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
            Column::make('name')->title(__('locations.country_name')),
            Column::make('code')->title(__('locations.country_code')),
            Column::make('governorates_count')->title(__('locations.governorates_count'))->searchable(false),
            Column::computed('actions')->title(__('messages.actions'))->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Countries_' . date('YmdHis');
    }
}

