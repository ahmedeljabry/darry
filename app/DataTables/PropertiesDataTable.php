<?php
declare(strict_types=1);

namespace App\DataTables;

use App\Domain\Enums\PropertyUseType;
use App\Models\Property;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PropertiesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('use_type', function (Property $p) {
                $val = $p->use_type instanceof PropertyUseType ? $p->use_type->value : (string) $p->use_type;
                return __('properties.use_types.' . $val);
            })
            // تعديل العنوان ليكون كاملاً
            ->addColumn('address', fn (Property $p) => $p->full_address)
            ->editColumn('coordinates' , fn (Property $p) => $p->coordinates ?: '--')
            ->addColumn('actions', function (Property $p) {
                return view('admin.layouts.partials._actions', [
                    'showRoute' => route('admin.properties.show', $p->id),
                    'editRoute' => route('admin.properties.edit', $p->id),
                    'deleteRoute' => route('admin.properties.destroy', $p->id),
                ])->render();
            })
            ->rawColumns(['actions'])
            ->setRowId('id');
    }

    public function query(Property $model): QueryBuilder
    {
        return $model->newQuery()->withCount('units')->select([
            'id','name','country','governorate','state','city','coordinates','use_type'
        ]);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('properties-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->buttons([Button::make('excel'), Button::make('csv'), Button::make('pdf'), Button::make('print')]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false),
            Column::make('name')->title(__('properties.name')),
            Column::computed('address')->title(__('properties.address')),
            Column::make('use_type')->title(__('properties.use_type')),
            Column::make('coordinates')->title(__('properties.coordinates')),
            Column::computed('actions')->title(__('messages.actions'))->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Properties_' . date('YmdHis');
    }
}
